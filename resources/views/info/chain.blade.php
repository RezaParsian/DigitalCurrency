@extends('info.master')

@section('body')
    <div class="col-span-3 grid md:grid-cols-4 gap-4 bg-white">
        @foreach($details as $detail)
            <div class="group flex flex-col justify-between rounded-sm bg-white p-4 shadow-xl transition-shadow hover:shadow-lg sm:p-6 lg:p-8">
                <div>
                    <h3 class="text-2xl font-bold text-indigo-600 whitespace-pre-line text-center">
                        {{$detail['name']}}
                    </h3>

                    <div class="mt-4 border-t-2 border-gray-100 pt-4">
                        <p class="text-sm font-medium uppercase text-gray-500 whitespace-pre-line text-center">
                            {{$detail['value']}}
                        </p>
                    </div>
                </div>

                <div class="mt-8 inline-flex items-center gap-2 text-indigo-600 sm:mt-12 lg:mt-16">
                    <p class="font-xs opacity-60 whitespace-pre-line">
                        {{$detail['description']}}
                    </p>
                </div>
            </div>
        @endforeach

        <hr/>

        @foreach($charts as $chart)
            <div class="block rounded-lg p-4 shadow-sm shadow-indigo-100">
                <img alt="{{$chart['name']}}" src="{{$chart['value']}}" class="h-56 w-full rounded-md object-cover"/>

                <div class="mt-2">
                    <dl>
                        <div>
                            <dd class="text-sm text-indigo-600 whitespace-pre-line">
                                {{$chart['name']}}
                            </dd>
                        </div>

                        <div>
                            <dt class="sr-only whitespace-pre-line">Address</dt>

                            <dd class="font-xs whitespace-pre-line">
                                {{$chart['description']}}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        @endforeach
    </div>
@endsection
