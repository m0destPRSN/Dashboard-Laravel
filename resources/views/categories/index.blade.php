@extends('adminlte::page')

@section('content_header')
    <h1>Всі категорії</h1>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('categories.create') }}" class="btn btn-success">Додати нову категорію</a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 10px">ID</th>
            <th>Фото</th>
            <th>Категорія</th>
            <th>Тип</th> {{-- Changed from ID типу and Тип to just Тип --}}
            <th style="width: 150px">Дії</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    @if($category->photo_path)
                        <img src="{{ asset('storage/' . $category->photo_path) }}" alt="{{ $category->category }}" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $category->category }}</td>
                {{-- Make sure your Category model has a 'type' relationship defined --}}
                {{-- e.g., public function type() { return $this->belongsTo(App\Models\Type::class, 'id_type'); } --}}
                <td>{{ $category->type->type ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Редагувати</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ви впевнені, що хочете видалити цю категорію?');">
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
