@extends('adminlte::page')

@section('title', 'Create Post')

@section('content_header')
    <h1>Create New Post</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"> {{-- Added enctype for file uploads --}}
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
                    <label for="link">Посилання (e.g. search?category=1)</label>
                    <input type="text" name="link" id="link" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" value="{{ old('link', $post->link ?? '') }}" required>
                    @if ($errors->has('link'))
                        <div class="invalid-feedback">
                            {{ $errors->first('link') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control-file {{ $errors->has('photo') ? 'is-invalid' : '' }}">
                    @if ($errors->has('photo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('photo') }}
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Створити посилання</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Відміна</a>
            </form>
        </div>
    </div>
@stop
