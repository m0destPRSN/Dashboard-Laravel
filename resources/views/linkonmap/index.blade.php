@extends('adminlte::page')

@section('title', 'Посилання на мапі')

@section('content_header')
    <h1>Посилання на мапі</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Список посилань на мапі</h3>
            <div class="card-tools">
                <a href="{{ route('links-on-map.create') }}" class="btn btn-primary">Додати нове посилання</a>
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
                    <th>#</th>
                    <th>Назва</th>
                    <th>Посилання</th>
                    <th>Фото</th>
                    <th>Створено</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @forelse($linkonmaps as $link)
                    <tr>
                        <td>{{ $link->id }}</td>
                        <td>{{ $link->name }}</td>
                        <td><a href="{{ url($link->link) }}" target="_blank">{{ url($link->link) }}</a></td>
                        <td>
                            @if($link->photo_path)
                                <img src="{{ asset('storage/' . $link->photo_path) }}" alt="{{ $link->name }}" style="max-width: 100px; max-height: 100px;">
                            @else
                                Немає фото
                            @endif
                        </td>
                        <td>{{ $link->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('links-on-map.edit', $link->id) }}" class="btn btn-xs btn-info">Редагувати</a>
                            <form action="{{ route('links-on-map.destroy', $link->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ви впевнені?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Посилань не знайдено</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $linkonmaps->links() }}
            </div>
        </div>
    </div>
@stop
