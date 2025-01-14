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

    <div style="width: 400px; height: 400px">
        <form method="post" action="{{ route('types.store') }}" class="mb-3">
            @csrf
            <div class="form-group">
                <label for="type">Новий тип</label>
                <input type="text" name="type" class="form-control" id="type" placeholder="Enter new type" required>
            </div>
            <button type="submit" class="btn btn-primary">Додати</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
