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
            <th>Запис створено</th>
            <th>Запис оновлено</th>
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
            <td><img width="200px" src="{{asset('storage/' . $location->photo_path)}}" alt=""></td>
            <td>{{$location->start_time.'-'.$location->end_time}}</td>
            <td>{{$location->created_at}}</td>
            <td>{{$location->updated_at}}</td>
        </tr>
    @endforeach
        </tbody>
    </table>
@endsection
