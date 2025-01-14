@extends('adminlte::page')
@section('content_header')
    <h1>Всі категорії</h1>
@endsection
@section('content')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 10px">ID категорії</th>
            <th>Категорія</th>
            <th style="width: 10px">ID типу</th>
            <th>Тип</th>
            <!--<th>Видалити категорію</th>-->
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{$category->id}}</td>
                <td>{{$category->category}}</td>
                <td>{{$category->id_type}}</td>
                <td>{{ optional($types->firstWhere('id', $category->id_type))->type }}</td>
                <!--<td><form action="categories/delete" method="post"> <input type="text" id="id" value="$category->id"> <button class="btn btn-danger">Видалити</button> </form></td>
           --> </tr>
        @endforeach
        </tbody>
    </table>
    <div style="width: 400px; height: 400px">
        <form method="post" action="{{ route('categories.store') }}" class="mb-3">
            @csrf
            <div class="form-group">
                <label for="category">Нова категорія </label>
                <input type="text" name="category" class="form-control" id="category" placeholder="Enter new category" required>
            </div>
            <select id="id_type" name="id_type" class="form-control mb-3" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">
                        {{ $type->type }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Додати</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
