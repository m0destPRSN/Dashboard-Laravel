@extends('adminlte::page')

@section('title', 'Редагувати посилання на мапі')

@section('content_header')
    <h1>Редагувати посилання на мапі</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('links-on-map.update', $linkonmap->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Назва посилання</label>
                    <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $linkonmap->name) }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="link">Посилання (наприклад, map?location=1)</label>
                    <input type="text" name="link" id="link" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" value="{{ old('link', $linkonmap->link) }}" required>
                    @if ($errors->has('link'))
                        <div class="invalid-feedback">
                            {{ $errors->first('link') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="photo">Фото</label>
                    <input type="file" name="photo" id="photo" class="form-control-file {{ $errors->has('photo') ? 'is-invalid' : '' }}">
                    @if ($errors->has('photo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('photo') }}
                        </div>
                    @endif
                    @if ($linkonmap->photo_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $linkonmap->photo_path) }}" alt="{{ $linkonmap->name }}" style="max-width: 150px; max-height: 150px;">
                            <p><small>Поточне фото. Завантажте нове, щоб замінити.</small></p>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Оновити посилання</button>
                <a href="{{ route('links-on-map.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@stop
