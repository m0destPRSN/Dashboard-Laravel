@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Створити нову локацію</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('locations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="location">Локація (Координати, наприклад, 50.258735, 28.603900)</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                            <small class="form-text text-muted">Ви можете клікнути на карту нижче, щоб встановити координати.</small>
                        </div>

                        <div id="map" style="height: 400px; width: 100%;" class="mb-3"></div>
                        <input type="hidden" id="map_location" name="map_location_input">


                        <div class="form-group mb-3">
                            <label for="id_type">Тип</label>
                            <select class="form-control" id="id_type" name="id_type" required>
                                <option value="">Виберіть тип</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('id_type') == $type->id ? 'selected' : '' }}>{{ $type->type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="id_category">Категорія</label>
                            <select class="form-control" id="id_category" name="id_category" required>
                                <option value="">Виберіть категорію</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('id_category') == $category->id ? 'selected' : '' }}>{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="title">Назва</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Опис</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="photos">Фото (можна вибрати декілька)</label>
                            <input type="file" class="form-control-file" id="photos" name="photos[]" multiple>
                        </div>

                        <div class="form-group mb-3">
                            <label for="start_time">Час початку</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="end_time">Час завершення</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Створити локацію</button>
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">Скасувати</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let map;
    let marker;
    const defaultLat = 50.4501; // Київ
    const defaultLng = 30.5234;

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        map = new Map(document.getElementById("map"), {
            center: { lat: defaultLat, lng: defaultLng },
            zoom: 6,
            mapId: "YOUR_MAP_ID" // Замініть на ваш Map ID, якщо він є
        });

        const locationInput = document.getElementById('location');

        // Ініціалізація маркера, якщо локація вже встановлена (наприклад, зі старого введення)
        if (locationInput.value) {
            const parts = locationInput.value.split(',');
            if (parts.length === 2) {
                const lat = parseFloat(parts[0].trim());
                const lng = parseFloat(parts[1].trim());
                if (!isNaN(lat) && !isNaN(lng)) {
                    const position = { lat, lng };
                    marker = new AdvancedMarkerElement({
                        map: map,
                        position: position,
                        gmpDraggable: true,
                    });
                    map.setCenter(position);
                    map.setZoom(10);
                }
            }
        }


        map.addListener("click", (mapsMouseEvent) => {
            const latLng = mapsMouseEvent.latLng.toJSON();
            locationInput.value = `${latLng.lat}, ${latLng.lng}`;

            if (marker) {
                marker.position = latLng;
            } else {
                marker = new AdvancedMarkerElement({
                    map: map,
                    position: latLng,
                    gmpDraggable: true, // Зробити маркер перетягуваним
                });
            }

            // Оновити поле вводу при перетягуванні маркера
            if (marker) {
                marker.addListener('dragend', (event) => {
                    const newPosition = marker.position;
                    locationInput.value = `${newPosition.lat}, ${newPosition.lng}`;
                });
            }
        });

        // Оновити карту, якщо поле вводу локації змінюється вручну
        locationInput.addEventListener('change', () => {
            const parts = locationInput.value.split(',');
            if (parts.length === 2) {
                const lat = parseFloat(parts[0].trim());
                const lng = parseFloat(parts[1].trim());
                if (!isNaN(lat) && !isNaN(lng)) {
                    const position = { lat, lng };
                    if (marker) {
                        marker.position = position;
                    } else {
                        marker = new AdvancedMarkerElement({
                            map: map,
                            position: position,
                            gmpDraggable: true,
                        });
                    }
                    map.setCenter(position);
                    if (marker) {
                        marker.addListener('dragend', (event) => {
                            const newPosition = marker.position;
                            locationInput.value = `${newPosition.lat}, ${newPosition.lng}`;
                        });
                    }
                }
            }
        });
    }

    initMap();
</script>

@include('footer.footer')
</body>
</html>
