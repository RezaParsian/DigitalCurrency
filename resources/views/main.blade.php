@extends('master.index')

@section('content')
    <coin-table :page="{{Request()->input('page',1)}}" class="my-5"></coin-table>

    <div class="mb-3">
        {{$paginator->render('vendor.pagination.tailwind')}}
    </div>
@endsection
