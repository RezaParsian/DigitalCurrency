@extends('info.master')

@section('body')
    <div class="col-span-3 grid grid-cols-1 gap-4">
        @foreach($wallets as $wallet)
            <article class="rounded-xl bg-white p-4 ring ring-indigo-50 sm:p-6 lg:p-8">
                <div class="grid md:flex">
                    <div class="grid h-20 w-20 shrink-0 place-content-center rounded-full border-2 border-indigo-500" aria-hidden="true">
                        <div class="flex items-center gap-1">
                            <img src="{{route('img',['img'=>base64_encode($wallet['image'])])}}" alt="{{$wallet['name']}}" class='rounded-full'/>
                        </div>
                    </div>

                    <div class="w-full">
                        <h3 class="mt-4 text-lg font-medium sm:text-xl">
                            <p class="hover:underline text-left">
                                {{$wallet['name']}}
                            </p>
                        </h3>

                        <div class="mt-1 grid md:grid-cols-12 text-sm text-gray-700 text-right gap-4 md:gap-12" dir='rtl'>
                            <div class="flex gap-4">
                                <span class="my-auto">امنیت:</span>
                                <img src="{{$wallet['security']}}" class="max-h-6 my-auto max-w-6" alt="{{$wallet['name']}}"/>
                            </div>

                            <div class="flex gap-4">
                                <span class="my-auto">ناشناسی:</span>
                                <span class="my-auto">{{$wallet['anonymity']}}</span>
                            </div>

                            <div class="flex gap-4 md:col-span-4">
                                <span class="my-auto">راحتی در استفاده:</span>
                                <span class="my-auto">{{$wallet['ease_of_use']}}</span>
                            </div>
                        </div>

                        <div class="mt-1 text-sm text-gray-700 text-right mt-6" dir='rtl'>
                            <p>ارزهای پشتیبانی شده:</p>
                            <p class="text-left">
                                {{implode(', ',$wallet['supported_coins'])}}
                            </p>
                        </div>

                        <div class="mt-4 sm:flex sm:items-center sm:gap-2">
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="svg-icon w-4" viewBox="0 0 20 20">
                                    <path
                                        d="M17.684,7.925l-5.131-0.67L10.329,2.57c-0.131-0.275-0.527-0.275-0.658,0L7.447,7.255l-5.131,0.67C2.014,7.964,1.892,8.333,2.113,8.54l3.76,3.568L4.924,17.21c-0.056,0.297,0.261,0.525,0.533,0.379L10,15.109l4.543,2.479c0.273,0.153,0.587-0.089,0.533-0.379l-0.949-5.103l3.76-3.568C18.108,8.333,17.986,7.964,17.684,7.925 M13.481,11.723c-0.089,0.083-0.129,0.205-0.105,0.324l0.848,4.547l-4.047-2.208c-0.055-0.03-0.116-0.045-0.176-0.045s-0.122,0.015-0.176,0.045l-4.047,2.208l0.847-4.547c0.023-0.119-0.016-0.241-0.105-0.324L3.162,8.54L7.74,7.941c0.124-0.016,0.229-0.093,0.282-0.203L10,3.568l1.978,4.17c0.053,0.11,0.158,0.187,0.282,0.203l4.578,0.598L13.481,11.723z"/>
                                </svg>

                                <p class="text-xs font-medium">{{$wallet['ratting']}}</p>
                            </div>

                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endsection
