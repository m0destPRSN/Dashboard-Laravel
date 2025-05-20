@include('head.head_doc')
<body>
@include('header.header')
@if(!empty($locations) && count($locations) > 0)
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                @foreach($locations as $location)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $location->photo_path) }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $location->title }}</h5>
                                <p class="card-text">{{ $location->description }}</p>
                                <div>
                                    <small class="text-muted d-block">
                                        {{ \App\Models\Category::find($location->id_category)->category }}
                                    </small>
                                    <a class="btn btn-outline-secondary disabled mt-2" href="#">
                                        Час роботи {{ $location->start_time . '-' . $location->end_time }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">{{ \App\Models\Type::find($location->id_type)->type }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="d-flex justify-content-center m-3">
        <p>Нічого не знайдено</p>
    </div>
@endif

@include('footer.footer')
</body>
