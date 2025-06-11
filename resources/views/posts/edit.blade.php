@extends('adminlte::page')

@section('title', 'Edit Post')

@section('content_header')
    <h1>Edit Post</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data"> {{-- Added enctype for file uploads --}}
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $post->name) }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="link">Посилання (e.g. search?category=1, search?type=2, search?category=1&type=2)</label>
                    <input type="text" name="link" id="link" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" value="{{ old('link', $post->link) }}" required>
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
                    @if ($post->photo_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $post->photo_path) }}" alt="{{ $post->name }}" style="max-width: 150px; max-height: 150px;">
                            <p><small>Current photo. Upload a new one to replace it.</small></p>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop
