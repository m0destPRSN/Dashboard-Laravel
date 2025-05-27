@extends('adminlte::page')

@section('content_header')
    <h1>Редагувати категорію</h1>
@endsection

@section('content')
    <div style="width: 400px;">
        <form method="post" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data" class="mb-3">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="category">Назва категорії</label>
                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" id="category" placeholder="Enter category name" value="{{ old('category', $category->category) }}" required>
                @error('category')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_type">Тип</label>
                <select id="id_type" name="id_type" class="form-control @error('id_type') is-invalid @enderror" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ (old('id_type', $category->id_type) == $type->id) ? 'selected' : '' }}>
                            {{ $type->type }}
                        </option>
                    @endforeach
                </select>
                @error('id_type')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="photo">Фото</label>
                <input type="file" name="photo" class="form-control-file @error('photo') is-invalid @enderror" id="photo">
                @error('photo')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
                @if($category->photo_path)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $category->photo_path) }}" alt="{{ $category->category }}" style="max-width: 100px; max-height: 100px;">
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Оновити</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Скасувати</a>
        </form>
    </div>
@endsection
