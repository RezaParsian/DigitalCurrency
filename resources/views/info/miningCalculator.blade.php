@extends('info.master')

@section('body')
    <mining-calculator :devices='@json($devices)' :coin-info='@json($coinInfo)' :coin='@json($coin)' :dollar-price="{{$fiats->USD->sanaprice}}"></mining-calculator>
@endsection
