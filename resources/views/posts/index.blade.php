@extends('adminlte::page')

@section('title', 'Posts')

@section('content_header')
    <h1>Posts</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Список посилань</h3>
            <div class="card-tools">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Створити нове посилання</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Photo</th>
                    <th>Created At</th>
                    <th style="width: 150px">Редагування</th>
                </tr>
                </thead>
                <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->name }}</td>
                        <td><a href="{{ url($post->link) }}" target="_blank">{{ url($post->link) }}</a></td>
                        <td>
                            @if($post->photo_path)
                                <img src="{{ asset('storage/' . $post->photo_path) }}" alt="{{ $post->name }}" style="max-width: 100px; max-height: 100px;">
                            @else
                                No Photo
                            @endif
                        </td>
                        <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-xs btn-info">Редагувати</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ви впевнені що хочете видалити цей пост?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Постів не знайдено</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $posts->links() }} {{-- For pagination --}}
            </div>
        </div>
    </div>
@stop
