@extends('info.master')

@section('body')
    <table>
        <thead>
        <tr>
            <th>#</th>
            @foreach($paginator[0] as $header=>$value)
                <th>{{$header}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($paginator as $paginate)
            <tr>
                <td>
                    {{((Request()->input('page',1)-1)*50)+$loop->index+1}}
                </td>
                @foreach($paginate as $key=>$value)
                    <td>{{$value}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mb-3">
        {{$paginator->render('vendor.pagination.tailwind')}}
    </div>
@endsection
