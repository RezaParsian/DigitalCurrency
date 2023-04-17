@extends('info.master')

@php
    $btcPrice= $fiats->BTC->sanaprice/$fiats->USD->sanaprice;
    $i=0;
    $slug = $coinInfo->slug;
    $buys = $deep['bids'];
    $sells =$deep['asks'];

    $tempBuy1 = [];
    $tempBuy2 = [];
    $buyT1Sum = 0;
    $buyDepth = 0;
    $buyAvgPrice=0;
    $buyPriceEffect=0;
    $buyEffectPercent=0;
    $buyPrice=1;
    $sellPrice=1;

    $tempSell1 = [];
    $tempSell2 = [];
    $sellT1Sum = 0;
    $sellDepth = 0;
    $sellAvgPrice=0;
    $sellPriceEffect=0;
    $sellEffectPercent=0;

    $vols =array_merge($tempBuy2,$tempSell2);
    $avgPrice = ($sellPrice + $buyPrice) / 2;
    $spreadVal = abs($sellPrice - $buyPrice);
    $spreadPercent = $spreadVal / $avgPrice;

    foreach ($buys as $i => $buy) {
        $t1 = (float) $buy[1];
        $t2 = $i == 0 ? $t1 : $t1 + $tempBuy1[$i - 1][1];
        $t3 = (float) $buy[0];
        if ($i === 0) {
        $buyPrice = $t3;
        }
        $tempBuy1[$i] = [$t3, $t2];
        $tempBuy2[$i] = [$t3, $t1];
        $buyT1Sum += $t1;
        $buyDepth += $t1 * $t3;
    }

    $buyAvgPrice = $buyDepth / $buyT1Sum;
    $buyPriceEffect = abs($buyAvgPrice - $buys[0][0]);
    $buyEffectPercent = $buyPriceEffect / $buyAvgPrice;

    array_reverse($tempBuy1);
    array_reverse($tempBuy2);

    foreach ($sells as $i => $sell) {
        $t1 = (float) $sell[1];
        $t2 = $i == 0 ? $t1 : $t1 + $tempSell1[$i - 1][1];
        $t3 = (float) $sell[0];

        if ($i === 0) {
            $sellPrice = $t3;
        }

        $tempSell1[$i] = [$t3, $t2];
        $tempSell2[$i] = [$t3, $t1];
        $sellT1Sum += $t1;
        $sellDepth += $t1 * $t3;
    }

    $sellAvgPrice = $sellDepth / $sellT1Sum;
    $sellPriceEffect = abs($sellAvgPrice - $sells[0][0]);
    $sellEffectPercent = $sellPriceEffect / $sellAvgPrice;

    if ($slug !== "bitcoin") {
        $buyDepth *= $btcPrice;
        $buyAvgPrice *= $btcPrice;
        $buyPriceEffect *= $btcPrice;
        $sellDepth *= $btcPrice;
        $sellAvgPrice *= $btcPrice;
        $sellPriceEffect *= $btcPrice;
    }
@endphp

@section('body')
    <div class="grid grid-cols-3 gap-5">
        <div class="col-span-2">
            <depth-chart :asks='@json($tempBuy1)'
                         :bids='@json($tempSell1)'>
            </depth-chart>

            <p class="text-justify my-auto" dir="rtl">
                نمودار عمق بازار چیست؟

                نمودار عمق یکی از نمودارهایی است که می توانید عرضه و تقاضا را در آن بررسی کنید. این نمودار یک تجسم فکری از عرضه و تقاضای یک سهام یا یک ارز را به ما ارائه می دهد. نمودار عمق اساسا عرضه و تقاضا را در قیمت های مختلف نشان می دهد. این
                نمودار در یک مارکت دارای دو خط یا قسمت است، یکی برای BID (سفارشات خرید) و دیگری برای ASK (سفارشات SELL). در اکثر صرافی ها رنگ خط Bid سبز و خط Ask قرمز است.

                پارامتر عمق خرید نشان‌دهنده کلیه سفارش‌های مربوط به خرید و عمق فروش نشان‌دهنده کلیه سفارش‌های فروش در بازه قیمتی مشخص شده است. میانگین قیمت در بخش‌های خرید و فروش، میانگین وزنی آن‌ها را با توجه به میزان سفارشات ثبت شده در قیمت‌های
                مختلف نشان می‌دهد. تاثیر قیمت پارامتری است که تفاوت میان آخرین قیمت معامله شده و میانگین قیمت هر یک از بخش‌های خرید یا فروش را نشان می‌دهد. درصد تاثیر نیز از تقسیم تاثیر قیمت به میانگین قیمت حاصل می‌شود.
            </p>
        </div>

        <div class="pt-4">
            <h2 class="text-2x text-bold">
                تاثیر عمق بازار
                {{$coinInfo->faname}}
            </h2>

            <table dir="rtl" class="w-full">
                <tr>
                    <td class="p-3 text-right">عمق خرید</td>
                    <td class="p-3 text-left text-green-700">
                        ${{number_format($buyDepth,5)}}
                    </td>
                </tr>
                <tr>
                    <td class="p-3 text-right">تاثیر قیمت</td>
                    <td class="p-3 text-left text-green-700">
                        ${{number_format($buyPriceEffect,5)}}
                    </td>
                </tr>
                <tr>
                    <td class="p-3 text-right">درصد تاثیر</td>
                    <td class="p-3 text-left text-green-700">
                        %{{number_format($buyEffectPercent,5)}}
                    </td>
                </tr>
                <tr>
                    <td class="p-3 text-right">میانگین قیمت</td>
                    <td class="p-3 text-left text-green-700">
                        ${{number_format($buyAvgPrice,5)}}
                    </td>
                </tr>
            </table>

            <hr class="my-5"/>

            <table dir="rtl" class="w-full">
                <tr>
                    <td class="p-3 text-right">عمق فروش</td>
                    <td class="p-3 text-left text-red-600">
                        ${{number_format($sellDepth,5)}}
                    </td>
                </tr>
                <tr>
                    <td class="p-3 text-right">تاثیر قیمت</td>
                    <td class="p-3 text-left text-red-600">
                        ${{number_format($sellPriceEffect,5)}}
                    </td>
                </tr>
                <tr>
                    <td class="p-3 text-right">درصد تاثیر</td>
                    <td class="p-3 text-left text-red-600">
                        %{{number_format($sellEffectPercent,5)}}
                    </td>
                </tr>
                <tr>
                    <td class="p-3 text-right">میانگین قیمت</td>
                    <td class="p-3 text-left text-red-600">
                        ${{number_format($sellAvgPrice,5)}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
