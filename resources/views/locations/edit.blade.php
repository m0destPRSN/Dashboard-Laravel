@extends('adminlte::page')

@section('content_header')
    <h1>Редагувати локацію</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Інформація про локацію</h3>
                    </div>
                    <form method="post" action="{{ route('admin.locations.update', $location->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Заголовок</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Введіть заголовок" value="{{ old('title', $location->title) }}" required>
                                @error('title')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Опис</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Введіть опис" required>{{ old('description', $location->description) }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="location">Координати (широта,довгота)</label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" id="location" placeholder="приклад: 50.4501,30.5234" value="{{ old('location', $location->location) }}" required>
                                @error('location')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Будь ласка, введіть координати у форматі "широта,довгота".</small>
                            </div>

                            <div class="form-group">
                                <label for="id_type">Тип</label>
                                <select id="id_type" name="id_type" class="form-control @error('id_type') is-invalid @enderror" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ (old('id_type', $location->id_type) == $type->id) ? 'selected' : '' }}>
                                            {{ $type->type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="id_category">Категорія</label>
                                <select id="id_category" name="id_category" class="form-control @error('id_category') is-invalid @enderror" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('id_category', $location->id_category) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_category')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="user_id">Власник локації</label>
                                <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                    @if(isset($users)) {{-- Ensure $users is passed to the view --}}
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('user_id', $location->user_id) == $user->id) ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->second_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                    @else
                                        <option value="">Користувачів не знайдено</option>
                                    @endif
                                </select>
                                @error('user_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="start_time">Час початку роботи</label>
                                        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" value="{{ old('start_time', $location->start_time) }}">
                                        @error('start_time')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="end_time">Час завершення роботи</label>
                                        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" value="{{ old('end_time', $location->end_time) }}">
                                        @error('end_time')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="photos">Фото (можна вибрати декілька)</label>
                                <input type="file" name="photos[]" class="form-control-file @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror" id="photos" multiple>
                                @error('photos')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                @error('photos.*')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if($location->photo_paths && count($location->photo_paths) > 0)
                                    <div class="mt-3">
                                        <p>Поточні фото:</p>
                                        <div>
                                            @foreach($location->photo_paths as $photo_path)
                                                <img src="{{ asset('storage/' . $photo_path) }}" alt="Location photo" style="max-width: 100px; max-height: 100px; margin-right: 10px; margin-bottom: 10px; border: 1px solid #ddd; padding: 2px;">
                                            @endforeach
                                        </div>
                                        <small class="form-text text-muted">Завантаження нових фото замінить існуючі.</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Оновити локацію</button>
                            <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Скасувати</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
