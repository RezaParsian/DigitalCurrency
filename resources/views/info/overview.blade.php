@extends('info.master')

@section('body')
    <div class="grid grid-cols-1 md:grid-cols-4">
        <div class="col-span-3">
            <h1 class="mb-4">
                درباره {{$coinInfo->faname}}
            </h1>

            <p class="mb-4">
                {{$detail}}
            </p>


            <h2 class="text-xl font-bold">
                نمودار تحلیل تکنیکال {{$coinInfo->faname}}
            </h2>

            <iframe src="https://s.tradingview.com/widgetembed/?frameElementId=tradingview_671b8&symbol={{$coinInfo->symbol}}USD&interval=1&hidesidetoolbar=0&symboledit=1&saveimage=1
                &toolbarbg=f1f3f6&studies=[]&theme=Light&style=1&timezone=Asia/Tehran&hideideasbutton=1&withdateranges=1&studies_overrides={}&overrides={}&enabled_features=[]
                &disabled_features=[]&utm_term={{$coinInfo->symbol}}USD" class="w-full h-[40rem]">
            </iframe>
        </div>

        <div class="col-span-1 border-r pr-2">
            <h1 class="mb-4 text-center">
                جزییات {{$coinInfo->faname}}
            </h1>

            <hr>

            <div class="grid">
                @foreach($pageDetails as $detail)
                    <div @class([
                        'grid',
                        'grid-cols-2',
                        'gap-2',
                        'justify-between',
                        'border-b-2'
                    ])>
                        <p class="text-right font-bold whitespace-pre-line">
                            {{$detail['title']}}
                        </p>
                        <p dir="ltr" class="{{str_replace(['%','$','#'],'',$detail['value'])<0 ? 'my-2 text-left text-red-600 ' : 'my-2 text-left text-green-800'}} whitespace-pre-line">
                            {{$detail['value']}}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
