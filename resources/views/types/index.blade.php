@extends('adminlte::page')
@section('content_header')
    <h1>Всі типи</h1>
@endsection
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Тип</th>
            <!--<th>Видалити тип</th>-->
        </tr>
        </thead>
        <tbody>
        @foreach($types as $type)
            <tr>
                <td>{{$type->id}}</td>
                <td>{{$type->type}}</td>
                <!--<td><form action="types/delete" method="post"> <input type="text" id="id" value="$type->id"> <button class="btn btn-danger">Видалити</button> </form></td>
           --> </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-primary" href="/types/add">
        <img width="40px" height="40px" src="{{ asset('images/add.svg') }}" alt="Додати" /> Додати категорію
    </a>
@endsection
