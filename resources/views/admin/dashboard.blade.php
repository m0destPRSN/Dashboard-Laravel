@extends('adminlte::page')
@section('content_header')
    <h1>Зареєстровані користувачі</h1>
@endsection
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Ім'я</th>
            <th>Прізвище</th>
            <th>Телефон</th>
            <th>Записано номер</th>
            <th>Верифіковано</th>
            <th>Зареєстровано остаточно</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->first_name}}</td>
                <td>{{$user->second_name}}</td>
                <td>{{$user->phone}}</td>
                <td>{{$user->created_at}}</td>
                <td>{{$user->phone_verified_at}}</td>
                <td>{{$user->updated_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
