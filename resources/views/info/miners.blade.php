@extends('info.master')

@section('body')
    <table dir="ltr">
        <thead>
        <tr>
            <th></th>
            <th>مدل دستگاه</th>
            <th>تاریخ عرضه</th>
            <th>مشخصات فنی دستگاه</th>
            <th>قیمت اصلی</th>
            <th>سود دهی</th>
            <th>وضعیت در بازار</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>
                    <img src="https://arzdigital.com/wp-content/themes/arz-theme/templates/arzDigital_mining/otherFiles/minerCompanyIcon/{{strtolower($row->company)}}.png" alt="{{$row->company}}"/>
                </td>

                <td>
                    <div class="py-2 text-left">
                        <strong class="text-sm">{{$row->device}}</strong>

                        <p class="text-slate-500">{{$row->company}}</p>
                    </div>
                </td>

                <td class="text-green-400">
                    {{$row->release}}
                </td>

                <td>
                    <div class="w-full grid grid-cols-2 gap-2">
                        <div>
                            <div class="border rounded-full flex justify-around my-2">
                                <div>
                                    <span class="max-w-12">{{$row->detail[0][0]}}</span>
                                    <span class="text-slate-500 text-[8pt]">{{$row->detail[0][1]}}</span>
                                </div>
                                <span class="border-l px-3">{{$row->detail[0][2]}}</span>
                            </div>

                            <div class="border rounded-full flex justify-around my-2">
                                <div>
                                    <span class="max-w-12">{{$row->detail[1][0]}}</span>
                                    <span class="text-slate-500 text-[8pt]">{{$row->detail[1][1]}}</span>
                                </div>
                                <span class="border-l px-3">{{$row->detail[1][2]}}</span>
                            </div>
                        </div>

                        <div>
                            <div class="border rounded-full flex justify-around my-2">
                                <div>
                                    <span class="max-w-12">{{$row->detail[2][0]}}</span>
                                    <span class="text-slate-500 text-[8pt]">{{$row->detail[2][1]}}</span>
                                </div>
                                <span class="border-l px-3">{{$row->detail[2][2]}}</span>
                            </div>

                            <div class="border rounded-full flex justify-around my-2">
                                <span>{{@$row->detail[3][0]}}</span>
                                <span class="border-l px-3">{{@$row->detail[3][1]}}</span>
                            </div>
                        </div>
                    </div>
                </td>

                <td>
                    {{$row->price}}
                </td>

                <td>
                    <strong dir="rtl" class="text-sm">{{$row->profits}}</strong>
                </td>

                <td>
                    {{$row->delivery}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
