@extends('master.index')

@section('content')
    <div id="tabs" class="flex gap-2 mt-4 w-full overflow-scroll px-3 pb-3">
        @foreach($tabs as $tab)
            <a href="{{Route::has($tab['name']) ? route($tab['name'],[$id,$symbol]) : '#'}}"
               class="p-3 border rounded min-w-max {{Route::current()->getAction('as')===$tab['name'] ? 'border-green-400 hover:border-green-500 hover:bg-green-400' : 'border-zinc-400 hover:border-zinc-500 hover:bg-zinc-400'}}">
                {{$tab['title']}}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-4">
        <area-chart :id="{{$id}}" class="col-span-3"></area-chart>

        <div class="p-2 grid-rows-5 my-auto">
            <div class="border-b-2 pb-3">
                <div class="flex mb-2" dir="ltr">
                    <img src="https://cdn.arzdigital.com/uploads/assets/coins/icons/{{$symbol}}.png" width="42px" alt="{{$symbol}}"/>
                    <h1>
                        {{ucfirst($symbol)}} ({{$coinInfo->symbol}})
                    </h1>
                </div>

                <div class="flex" dir="ltr">
                    <svg width="24px" height="24px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#000000"
                              d="M255 471L91.7 387V41h328.6v346zm-147.3-93.74L255 453l149.3-75.76V57H107.7v320.26zm187.61-168.34l-14.5-46 38.8-28.73-48.27-.43L256 87.94l-15.33 45.78-48.27.43 38.8 28.73-14.5 46 39.31-28zM254.13 311.5l98.27-49.89v-49.9l-98.14 49.82-94.66-48.69v50zm.13 32.66l-94.66-48.69v50l94.54 48.62 98.27-49.89v-49.9z"/>
                    </svg>
                    <span class="my-auto font-thin">
                        Rank #{{$coinInfo->rank}}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-8 my-8 border-b-2 pb-6">
                <div @class([
                    'rounded-full',
                    'border',
                    'max-w-[4rem]',
                    'max-h-[4rem]',
                    'inline-flex',
                    'col-span-2',
                    "bg-green-500"=>$coinInfo->d1>=0,
                    "bg-rose-500"=>$coinInfo->d1<0
                ])>
                    <div class="flex w-full">
                        <span dir="ltr" class="my-auto mx-auto">
                        {{number_format($coinInfo->d1,2)}}%
                    </span>
                    </div>
                </div>

                <div class="col-span-6">
                    <h1>
                        ${{number_format($coinInfo->price)}}
                    </h1>

                    <h2 class="my-auto text-sm text-zinc-600 font-bold">
                        {{number_format($coinInfo->price * $fiats->USD->sanaprice)}} تومان
                    </h2>

                    <h3 class="my-auto text-xs text-zinc-600 font-bold">
                        محاسبه با نرخ تتر: {{number_format($fiats->USD->sanaprice)}} تومان
                    </h3>
                </div>
            </div>

            <div class="col-span-2 grid grid-cols-2 text-center">
                <div>
                    <p class="text-s text-gray-700">
                        معاملات روزانه
                    </p>

                    <span class="text-xs text-gray-600">
                        ${{number_format($coinInfo->volume24h)}}
                    </span>
                </div>

                <div>
                    <p class="text-s text-gray-700">
                        حجم بازار
                    </p>

                    <span class="text-xs text-gray-600">
                        ${{number_format($coinInfo->marketcap)}}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @yield('body')
@endsection
