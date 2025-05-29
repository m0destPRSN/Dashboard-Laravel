@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])


<div class="container mt-4 mb-5">
    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">


            {{-- Image --}}
            @if($location->photo_path)
                <div class="mb-4 shadow-sm rounded">
                    <img src="{{ asset('storage/' . $location->photo_path) }}" class="img-fluid rounded" alt="{{ $location->title }}" style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
            @endif

            {{-- Description Card --}}
            @if($location->description)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">{{ $location->title }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead">{{ $location->description }}</p>
                    </div>
                </div>
            @endif

            {{-- Location Details Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Деталі</h4>
                </div>
                <div class="card-body">
                    @if(isset($type))
                        <p><strong>Тип:</strong> {{ $type->type }}</p>
                    @endif
                    @if(isset($category))
                        <p><strong>Категорія:</strong> {{ $category->category }}</p>
                    @endif
                    {{-- Opening hours moved to sidebar for this example layout, but can be here too --}}
                </div>
            </div>

            {{-- Placeholder for Features/Amenities Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Особливості та зручності</h4>
                </div>
                <div class="card-body">
                    <p><em>(Тут буде список особливостей та зручностей. Наприклад: Wi-Fi, парковка, тощо)</em></p>
                    {{-- Example:
                    <ul class="list-unstyled row">
                        <li class="col-md-6"><i class="fas fa-check text-success mr-2"></i> Wi-Fi</li>
                        <li class="col-md-6"><i class="fas fa-check text-success mr-2"></i> Парковка</li>
                        <li class="col-md-6"><i class="fas fa-times text-danger mr-2"></i> Дозволено з тваринами</li>
                    </ul>
                    --}}
                </div>
            </div>

            {{-- Map Card --}}
            @if($location->location)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Місцезнаходження на карті</h4>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>
                </div>
            @endif

            {{-- Placeholder for Reviews Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Відгуки</h4>
                </div>
                <div class="card-body">
                    <p><em>(Тут будуть відгуки користувачів)</em></p>
                    {{-- Example review item --}}
                    {{--
                    <div class="media mb-3 border-bottom pb-3">
                        <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="User Avatar">
                        <div class="media-body">
                            <h5 class="mt-0 mb-1">Ім'я користувача <small class="text-muted">- 5 зірок</small></h5>
                            <p>Чудове місце! Дуже сподобалось обслуговування та атмосфера.</p>
                            <small class="text-muted">Дата відгуку</small>
                        </div>
                    </div>
                    --}}
                    <h5 class="mt-4">Залишити відгук</h5>
                    <form>
                        <div class="form-group">
                            <label for="reviewName">Ваше ім'я</label>
                            <input type="text" class="form-control" id="reviewName" placeholder="Ім'я">
                        </div>
                        <div class="form-group">
                            <label for="reviewRating">Оцінка</label>
                            <select class="form-control" id="reviewRating">
                                <option>5 зірок</option>
                                <option>4 зірки</option>
                                <option>3 зірки</option>
                                <option>2 зірки</option>
                                <option>1 зірка</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reviewText">Ваш відгук</label>
                            <textarea class="form-control" id="reviewText" rows="3" placeholder="Напишіть ваш відгук"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Надіслати відгук</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">
            {{-- Placeholder for Booking/Contact Form Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Забронювати / Зв'язатися</h5>
                </div>
                <div class="card-body">
                    <p><em>(Тут може бути форма бронювання або контактна інформація)</em></p>
                    <form>
                        <div class="form-group">
                            <label for="sidebarContactName">Ім'я</label>
                            <input type="text" class="form-control" id="sidebarContactName">
                        </div>
                        <div class="form-group">
                            <label for="sidebarContactEmail">Email</label>
                            <input type="email" class="form-control" id="sidebarContactEmail">
                        </div>
                        <div class="form-group">
                            <label for="sidebarContactMessage">Повідомлення</label>
                            <textarea class="form-control" id="sidebarContactMessage" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Надіслати</button>
                    </form>
                </div>
            </div>

            {{-- Opening Hours Card --}}
            @if($location->start_time && $location->end_time)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Години роботи</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead"><strong>{{ $location->start_time }} - {{ $location->end_time }}</strong></p>
                    </div>
                </div>
            @endif

            {{-- Placeholder for Author/Listed By Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Інформація про власника</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/100/007bff/ffffff?Text=User" class="rounded-circle mb-3" alt="Author Avatar">
                    <h5>Ім'я Власника</h5>
                    <p class="text-muted">Учасник з {{ $location->created_at->format('Y') }} року</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Переглянути профіль</a>
                </div>
            </div>

            {{-- Placeholder for Similar Listings Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Схожі місця</h5>
                </div>
                <div class="list-group list-group-flush">
                    {{-- Example item:
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Назва схожого місця 1</h6>
                        </div>
                        <small class="text-muted">Категорія, Тип</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Назва схожого місця 2</h6>
                        </div>
                        <small class="text-muted">Категорія, Тип</small>
                    </a>
                    --}}
                    <p class="p-3"><em>(Тут буде список схожих місць)</em></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Record Timestamps --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="text-muted border-top pt-3 text-center">
                Запис створено: {{ $location->created_at->format('d.m.Y H:i') }}
                @if($location->updated_at != $location->created_at)
                    | Оновлено: {{ $location->updated_at->format('d.m.Y H:i') }}
                @endif
            </div>
        </div>
    </div>
</div>

@if($location->location)
    <script>
        let map;
        const locationString = "{{ $location->location }}";
        let lat, lng;

        if (locationString) {
            const coordinates = locationString.replace(/\s/g, '').split(',');
            if (coordinates.length === 2) {
                lat = parseFloat(coordinates[0]);
                lng = parseFloat(coordinates[1]);
            }
        }

        async function initMap() {
            const mapElement = document.getElementById("map");
            if (!mapElement) {
                console.error("Map element not found.");
                return;
            }

            if (isNaN(lat) || isNaN(lng)) {
                mapElement.innerHTML = "<p class='text-danger p-3'>Не вдалося завантажити координати для карти. Перевірте формат координат.</p>";
                return;
            }

            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                console.error("Google Maps API not loaded.");
                mapElement.innerHTML = "<p class='text-danger p-3'>Не вдалося завантажити Google Maps API. Можливо, проблема з підключенням до Інтернету або ключ API недійсний.</p>";
                return;
            }

            try {
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker"); // Corrected: AdvancedMarkerElement

                map = new Map(mapElement, {
                    center: { lat: lat, lng: lng },
                    zoom: 15,
                    mapId: '{{env("GOOGLE_MAP_ID", "DEMO_MAP_ID")}}' // Use a specific Map ID for cloud styling or a fallback/omit if not used.
                    // Using API key directly as mapId is generally incorrect.
                });

                const marker = new AdvancedMarkerElement({ // Corrected: AdvancedMarkerElement
                    map: map,
                    position: { lat: lat, lng: lng },
                    title: "{{ addslashes($location->title) }}" // Use addslashes for title in JS
                });
            } catch (error) {
                console.error("Error initializing map:", error);
                mapElement.innerHTML = "<p class='text-danger p-3'>Помилка при ініціалізації карти. Деталі в консолі розробника.</p>";
            }
        }
        // initMap will be called by the Google Maps API callback specified in head_doc.blade.php
        // Ensure `initMap` is globally accessible if it's not already.
        // If `callback=initMap` is not in the script URL, you would call `initMap()` here,
        // after checking for `google.maps` availability.
    </script>
@endif

@include('footer.footer')
</body>
</html>
