@extends('adminlte::page')

@section('content_header')
    <h1>Редагувати тип</h1>
@endsection

@section('content')
    <div style="width: 400px;">
        <form method="post" action="{{ route('types.update', $type->id) }}" enctype="multipart/form-data" class="mb-3">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="type">Назва типу</label>
                <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" id="type" placeholder="Enter type name" value="{{ old('type', $type->type) }}" required>
                @error('type')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="photo">Фото</label>
                <input type="file" name="photo" class="form-control-file @error('photo') is-invalid @enderror" id="photo">
                @error('photo')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
                @if($type->photo_path)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $type->photo_path) }}" alt="{{ $type->type }}" style="max-width: 100px; max-height: 100px;">
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Оновити</button>
            <a href="{{ route('types.index') }}" class="btn btn-secondary">Скасувати</a>
        </form>
    </div>
@endsection
