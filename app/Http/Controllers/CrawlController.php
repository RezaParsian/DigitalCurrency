<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as ViewAlias;
use phpQuery;
use Rp76\Guzzle\Client;
use WebSocket\Client as websocket;

require(public_path() . '/../vendor/george-zakharov/php-query/phpQuery/phpQuery.php');

class CrawlController extends Controller
{
    public function __construct()
    {
        $coinInfo = $this->coinInfo(Route::current()->parameter('id'))->data;
        $fiats = $this->fiats()->data;
        $tabs = $this->tab(Route::current()->parameter('symbol'));

        ViewAlias::share('coinInfo', $coinInfo);
        ViewAlias::share('fiats', $fiats);
        ViewAlias::share('tabs', $tabs);
    }

    /**
     * @param string $url
     * @param null $ttl
     * @return string
     */
    protected function crawl(string $url, $ttl = null): string
    {
        return Cache::remember($url, $ttl ?? now()->addDay(), function () use ($url) {
            $client = new Client([
                'timeout' => 5.0,
                'proxy' => 'socks5://127.0.0.1:1060'
            ]);
            return $client->easySend(new Request('GET', $url))->body;
        });
    }

    public function coinInfo($coinId)
    {
        return Cache::remember($coinId, now()->addDay(), function () use ($coinId) {
            $socket = new websocket('wss://ws2.arzdigital.com/', ['timeout' => 60,]);

            $socket->text('{"action":"coin","key":"2","cid":"' . $coinId . '"}');
            return json_decode($socket->receive());
        });
    }

    public function fiats()
    {
        return Cache::remember('fiats', now()->addDay(), function () {
            $socket = new websocket('wss://ws2.arzdigital.com/', ['timeout' => 60,]);

            $socket->text('{"action":"fiats","key":"1"}');
            return json_decode($socket->receive());
        });
    }

    public function tab(string $symbol): array
    {
        $tabs = [];
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/");

        phpQuery::newDocument($source);

        foreach (pq('li.arz-slide-menu-item.arz-coin-page-tab') as $tab) {
            $name = pq($tab)->attr('tab_name');

            if ($name === "buy-sell") {
                continue;
            }

            $tabs[] = array(
                'name' => $name,
                'title' => trim(pq($tab)->find('a')->text()),
            );
        }

        return $tabs;
    }

    /**
     * @param string $id
     * @param string $symbol
     * @return Application|Factory|View
     */
    public function overview(string $id, string $symbol)
    {
        $pageDetails = [];
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/");

        $doc = phpQuery::newDocument($source);

        foreach ($doc->find('table.arz-table.arz-coin-general-info-table tr') as $row) {
            $title = pq($row)->find('th')->text();
            $value = pq($row)->find('td')->text();

            if (str_contains($title, "\n"))
                continue;

            $pageDetails[] = [
                'title' => trim($title),
                'value' => trim($value),
            ];
        }

        $detail = pq('.arz-coin-details__explanation-text')->text();

        return view('info.overview', compact('pageDetails', 'id', 'symbol', 'detail'));
    }

    public function orderBook(string $id, string $symbol): array
    {
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/order-book/");
        $pattern = "/<script type=\'text\\/javascript\' src=\'(.*?)\'.*?><\\/script>/m";

        preg_match_all($pattern, $source, $matches);

        $foundedItem = array_filter($matches[1], fn($item) => strpos($item, 'assets/js/tmp') !== false);

        $source = $this->crawl(end($foundedItem));
        $pattern = "/const coin_depth_info.*=.*{marketdepth:(.*?)}/";

        preg_match_all($pattern, $source, $matches);

        return json_decode($matches[1][0] . "}", true);
    }

    public function historicalData(\Illuminate\Http\Request $request, string $id, string $symbol): array
    {
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/historical-data/page-" . $request->input('page', 1) . "/");
        $rows = [];

        $doc = phpQuery::newDocument($source);

        $foundedItem = pq('.arz-coin-pagination__item');
        $last_page = $foundedItem->eq($foundedItem->length - 2)->attr('page');

        $headers = [];
        $rows = [];

        $table = $doc->find('.arz-table.arz-historical-data-table');
        $table->find('th')->each(function ($header) use (&$headers) {
            $headers[] = pq($header)->text();
        });

        $table->find('tbody')->find('tr')->each(function ($row) use (&$rows, $headers) {
            $cells = pq($row)->find('td');
            $rowData = [];
            foreach ($cells as $j => $cell) {
                $rowData[$headers[$j]] = pq($cell)->text();
            }
            $rows[] = $rowData;
        });

        return [
            'rows' => $rows,
            'last_page' => $last_page
        ];
    }

    public function chain(string $id, string $symbol): array
    {
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/chain/");
        $details = [];
        $charts = [];

        $doc = phpQuery::newDocument($source);

        $doc->find('.arz-coin-details-chain__info')->find('.arz-coin-details-chain__info-card')->each(function ($card) use (&$details) {
            $card = pq($card);
            $details[] = [
                'name' => trim($card->find('.arz-coin-details-chain__info-name')->text()),
                'value' => trim($card->find('.arz-coin-details-chain__info-value-box')->text()),
                'description' => trim($card->find('.arz-coin-details-chain__info-desc')->text()),
            ];
        });

        $doc->find('.arz-coin-details-chart-info__card')->each(function ($card) use (&$charts) {
            $card = pq($card);
            $charts[] = [
                'name' => trim($card->find('.arz-coin-details-chart-info__name')->text()),
                'value' => trim($card->find('.arz-coin-details-chart-info__chart')->attr('data-src')),
                'description' => trim($card->find('.arz-coin-details-chart-info__desc')->text()),
            ];
        });

        return [
            "details" => $details,
            "charts" => $charts,
        ];
    }

    public function wallet(string $id, string $symbol): array
    {
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/wallet/");

        phpQuery::newDocument($source);

        $wallets = [];

        foreach (pq('.arz-wallet.arz-sort-value-row.arz-scroll-height-box') as $wallet) {
            $name = pq($wallet)->find('.arz-wallet__name')->text();
            $image = pq($wallet)->find('.arz-wallet__icon img')->attr('data-src');
            $security = pq($wallet)->find('.arz-wallet__security img')->attr('data-src');
            $anonymity = pq($wallet)->find('.arz-wallet__anonymity')->text();
            $ease_of_use = pq($wallet)->find('.arz-wallet__easeofuse')->text();
            $ratting = pq($wallet)->find('.arz-rating')->attr('data-rate');

            $wallets[] = [
                'name' => trim($name),
                'ratting' => trim($ratting),
                'image' => trim($image),
                'security' => trim($security),
                'anonymity' => trim($anonymity),
                'ease_of_use' => trim($ease_of_use),
                'supported_coins' => array_filter(explode("\n", pq($wallet)->find('.arz-wallet__supported-coins-list a')[0]->text()))
            ];
        }

        return $wallets;
    }

    /**
     * @param string $id
     * @param string $symbol
     * @return array
     */
    public function miningCalculator(string $id, string $symbol): array
    {
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/mining-calculator/");
        $pattern = "/miningPageData = (.*),\\W*isCoinDedicatePage/ms";

        preg_match_all($pattern, $source, $matches);

        return json_decode($matches[1][0], true);
    }

    public function miners(string $id, string $symbol): array
    {
        $source = $this->crawl("https://arzdigital.com/coins/$symbol/miners/");

        phpQuery::newDocument($source);

        $tableRows = pq('.arz-scroll-body')->find('tr');

        $rows = [];

        foreach ($tableRows as $row) {
            $pqRow = pq($row);

            $details = $pqRow->find('.fanni')->map(function ($a) {
                $items = explode("\n", pq($a)->find('li')->text());

                return array_map(function ($item) {
                    return array_filter(explode(' ', $item));
                }, $items);
            })->elements;

            $rows[] = [
                'device' => $pqRow->find('.device')->text(),
                'company' => $pqRow->find('.company')->text(),
                'price' => $pqRow->find('.price9')->text(),
                'profits' => $pqRow->find('.profits')->text(),
                'delivery' => $pqRow->find('.delivery9')->text(),
                'release' => $pqRow->find('.release')->text(),
                'detail' => array_filter($details),
            ];
        }


        return $rows;
    }

    public function coinHistory(\Illuminate\Http\Request $request)
    {
        $client = new Client([
            'timeout' => 5.0,
            'proxy' => 'socks5://127.0.0.1:1060'
        ]);

        return $client->easySend(new Request('POST', 'https://api.arzdigital.com/history/'), [
            "form_params" => array_filter([
                'action' => 'arzajax2',
                'gethistory' => $request->input('coin_id'),
                'range' => $request->input('range'),
                'dollar' => $request->has('dollar')
            ]),
        ])->json;
    }
}
