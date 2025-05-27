@extends('adminlte::page')
@section('content_header')
    <h1>Всі типи</h1>
@endsection
@section('content')
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('types.create') }}" class="btn btn-success">Додати новий тип</a>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Фото</th>
            <th>Тип</th>
            <th style="width: 150px">Дії</th>
        </tr>
        </thead>
        <tbody>
        @foreach($types as $type)
            <tr>
                <td>{{$type->id}}</td>
                <td>
                    @if($type->photo_path)
                        <img src="{{ asset('storage/' . $type->photo_path) }}" alt="{{ $type->type }}" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{$type->type}}</td>
                <td>
                    <a href="{{ route('types.edit', $type->id) }}" class="btn btn-sm btn-primary">Редагувати</a>
                    <form action="{{ route('types.destroy', $type->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ви впевнені, що хочете видалити цей тип?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
