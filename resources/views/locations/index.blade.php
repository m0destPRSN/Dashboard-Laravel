@extends("adminlte::page")
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Координати</th>
            <th style="width: 20px">Тип</th>
            <th style="width: 20px">Категорія</th>
            <th>Заголовок</th>
            <th>Опис</th>
            <th>Фото</th>
            <th>Час роботи</th>
            <th>Власник</th> {{-- New Column --}}
            <th>Запис створено</th>
            <th>Запис оновлено</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        @foreach($locations as $location)
            <tr>
                <td>{{$location->id}}</td>
                <td>{{$location->location}}</td>
                <td>{{optional($types->firstWhere('id', $location->id_type))->type }}</td>
                <td>{{optional($categories->firstWhere('id', $location->id_category))->category }}</td>
                <td>{{$location->title}}</td>
                <td>{{$location->description}}</td>
                <td>
                    @if(is_array($location->photo_paths))
                        @foreach($location->photo_paths as $photo_path)
                            <img width="100px" src="{{asset('storage/' . $photo_path)}}" alt="Location photo" style="margin-right: 5px; margin-bottom: 5px;">
                        @endforeach
                    @elseif($location->photo_paths)
                        <img width="200px" src="{{asset('storage/' . $location->photo_paths)}}" alt="Location photo">
                    @else
                        No photo
                    @endif
                </td>
                <td>{{$location->start_time.'-'.$location->end_time}}</td>
                {{-- Display Owner Name --}}
                <td>
                    @if($location->user)
                        {{ $location->user->first_name }} {{ $location->user->second_name }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{$location->created_at}}</td>
                <td>{{$location->updated_at}}</td>
                <td>
                    <a href="{{ route('admin.locations.edit', $location->id) }}" class="btn btn-xs btn-info" style="margin-right: 5px;">Редагувати</a>
                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Ви впенені що хочете видалити цю локацію?');" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-xs btn-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
