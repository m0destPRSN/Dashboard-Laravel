@extends('adminlte::page')

@section('content_header')
    <h1>Додати нову категорію</h1>
@endsection

@section('content')
    <div style="width: 400px;">
        <form method="post" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="form-group">
                <label for="category">Назва категорії</label>
                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" id="category" placeholder="Enter category name" value="{{ old('category') }}" required>
                @error('category')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_type">Тип</label>
                <select id="id_type" name="id_type" class="form-control @error('id_type') is-invalid @enderror" required>
                    <option value="">Оберіть тип</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('id_type') == $type->id ? 'selected' : '' }}>
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
            </div>

            <button type="submit" class="btn btn-primary">Додати</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Скасувати</a>
        </form>
    </div>
@endsection
