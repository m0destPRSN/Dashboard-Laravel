@extends('adminlte::page')
@section('content')
    <div style="width: 400px; height: 400px">
        <form method="post" action="{{ route('types.store') }}" class="mb-3">
            @csrf
            <div class="form-group">
                <label for="type">Новий тип</label>
                <input type="text" name="type" class="form-control" id="type" placeholder="Enter new type" required>
            </div>
            <button type="submit" class="btn btn-primary">Додати</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
