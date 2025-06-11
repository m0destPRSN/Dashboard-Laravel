@extends('adminlte::page')

@section('title', 'Додати посилання на мапі')

@section('content_header')
    <h1>Додати нове посилання на мапі</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('links-on-map.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Назва посилання</label>
                    <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="link">Посилання (наприклад, map?location=1)</label>
                    <input type="text" name="link" id="link" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" value="{{ old('link') }}" required>
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
                </div>

                <button type="submit" class="btn btn-primary">Додати посилання</button>
                <a href="{{ route('links-on-map.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@stop
