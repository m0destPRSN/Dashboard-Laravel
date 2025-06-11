@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])

<style>
    #locationPhotosCarousel .carousel-item img {
        height: 400px;
        width: 100%;
        object-fit: cover;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">

            {{-- Image Slider --}}
            @if(!empty($location->photo_paths))
                <div id="locationPhotosCarousel" class="carousel slide mb-4 shadow-sm rounded" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @if(is_array($location->photo_paths))
                            @foreach($location->photo_paths as $key => $path)
                                <li data-target="#locationPhotosCarousel" data-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                            @endforeach
                        @else
                            <li data-target="#locationPhotosCarousel" data-slide-to="0" class="active"></li>
                        @endif
                    </ol>
                    <div class="carousel-inner rounded">
                        @if(is_array($location->photo_paths))
                            @foreach($location->photo_paths as $path)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $path) }}" class="d-block w-100" alt="{{ $location->title }}" style="max-height: 500px; object-fit: cover;">
                                </div>
                            @endforeach
                        @else
                            {{-- Fallback for single image path if not an array (old structure) --}}
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $location->photo_paths) }}" class="d-block w-100" alt="{{ $location->title }}" style="max-height: 500px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                    @if(is_array($location->photo_paths) && count($location->photo_paths) > 1)
                        <a class="carousel-control-prev" href="#locationPhotosCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#locationPhotosCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    @endif
                </div>
            @elseif($location->photo_path) {{-- Legacy single photo_path field --}}
            <div class="mb-4 shadow-sm rounded">
                <img src="{{ asset('storage/' . $location->photo_path) }}" class="img-fluid rounded" alt="{{ $location->title }}" style="width: 100%; max-height: 500px; object-fit: cover;">
            </div>
            @endif

            {{-- Description Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ $location->title }}</h4>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $location->description }}</p>
                </div>
            </div>


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
            {{-- Placeholder for Reviews Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Відгуки</h4>
                </div>
                <div class="card-body">
                    {{-- Відображення існуючих відгуків --}}
                    @if($location->reviews && $location->reviews->count() > 0)
                        <h5 class="mb-3">Існуючі відгуки ({{ $location->reviews->count() }})</h5>
                        @foreach($location->reviews as $review)
                            @php
                                $reviewUser = $review->user;
                                $isNotCurrentUser = $reviewUser && $reviewUser->id !== auth()->id();
                            @endphp

                            @if($reviewUser && $isNotCurrentUser)
                                <div class="media mb-3 border-bottom pb-3 align-items-start">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reviewUser->first_name . ' ' . $reviewUser->second_name) }}&background=random&size=64"
                                         class="mr-3 rounded-circle" alt="Avatar">
                                    <div class="media-body" style="position:relative;">
                                        <h5 class="mt-0 mb-1">
                        <span class="open-user-chat-popup"
                              data-user-id="{{ $reviewUser->id }}"
                              data-user-name="{{ $reviewUser->first_name }} {{ $reviewUser->second_name }}"
                              style="color: #212529; cursor: pointer; font-weight: 500;"
                              title="Написати повідомлення">
                            {{ $reviewUser->first_name }} {{ $reviewUser->second_name }}
                        </span>
                                            <small class="text-muted">-
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                ({{ $review->rating }}/5)
                                            </small>
                                            <div class="user-chat-popup shadow-sm"
                                                 style="display:none; position:absolute; z-index:1000; left:0; top:2.2em; background:#fff; border:1px solid #ddd; border-radius:6px; padding:10px 16px; min-width:180px;">
                                                <div style="font-size: 0.95em; margin-bottom: 8px;">
                                                    <span class="popup-user-name"></span>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-sm goToUserChat">написати повідомлення</button>
                                                <button type="button" class="close close-user-chat-popup"
                                                        style="position:absolute; top:4px; right:8px; font-size:1.1em;">&times;</button>
                                            </div>
                                        </h5>
                                        <p>{{ $review->review_text }}</p>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @elseif(!$reviewUser)
                                <div class="media mb-3 border-bottom pb-3">
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-1 text-muted">Відгук від видаленого користувача
                                            <small class="text-muted">-
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                ({{ $review->rating }}/5)
                                            </small>
                                        </h5>
                                        <p>{{ $review->review_text }}</p>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p><em>Ще немає відгуків для цього місця. Будьте першим!</em></p>
                    @endif

                    {{-- Popup chat JS --}}
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            let currentPopup = null;

                            $(document).on('click', '.open-user-chat-popup', function(e) {
                                e.stopPropagation();
                                $('.user-chat-popup').hide();
                                const $span = $(this);
                                const $popup = $span.closest('.media-body').find('.user-chat-popup');
                                $popup.find('.popup-user-name').text($span.data('user-name'));
                                $popup.show();
                                currentPopup = $popup;
                                $popup.find('.goToUserChat').data('user-id', $span.data('user-id'));
                            });

                            $(document).on('click', '.goToUserChat', function() {
                                const userId = $(this).data('user-id');
                                if (userId) {
                                    window.location.href = '/chat/user/' + userId;
                                }
                            });

                            $(document).on('click', '.close-user-chat-popup', function(e) {
                                $(this).closest('.user-chat-popup').hide();
                                e.stopPropagation();
                            });

                            $(document).on('click', function() {
                                $('.user-chat-popup').hide();
                            });

                            $(document).on('click', '.user-chat-popup', function(e) {
                                e.stopPropagation();
                            });
                        });
                    </script>


                    <h5 class="mt-0">Залишити відгук</h5>
                    @auth
                        {{-- Повідомлення про успіх/помилку --}}
                        @if(session('success_review'))
                            <div class="alert alert-success">
                                {{ session('success_review') }}
                            </div>
                        @endif
                        @if(session('error_review'))
                            <div class="alert alert-danger">
                                {{ session('error_review') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('reviews.store', $location->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="reviewName">Ваше ім'я</label>
                                {{-- Поле імені тепер просто відображає ім'я поточного користувача і не надсилається з формою --}}
                                <input type="text" class="form-control" id="reviewName" value="{{ Auth::user()->first_name }} {{ Auth::user()->second_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="reviewRating">Оцінка</label>
                                <select class="form-control @error('rating') is-invalid @enderror" id="reviewRating" name="rating">
                                    <option value="">Оберіть оцінку</option>
                                    <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 зірок</option>
                                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 зірки</option>
                                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 зірки</option>
                                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 зірки</option>
                                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 зірка</option>
                                </select>
                                @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="reviewText">Ваш відгук</label>
                                <textarea class="form-control @error('review_text') is-invalid @enderror" id="reviewText" name="review_text" rows="3" placeholder="Напишіть ваш відгук" required>{{ old('review_text') }}</textarea>
                                @error('review_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Надіслати відгук</button>
                        </form>
                    @else
                        <div class="alert alert-warning" role="alert">
                            Тільки авторизовані користувачі можуть залишати відгук. <a href="{{ route('login') }}">Увійдіть</a>, щоб залишити відгук.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">

            {{-- Opening Hours Card --}}
            @if($location->start_time && $location->end_time)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Години роботи</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead"><strong>{{ \Carbon\Carbon::parse($location->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($location->end_time)->format('H:i') }}</strong></p>
                    </div>
                </div>
            @endif

            {{-- Placeholder for Author/Listed By Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Інформація про власника</h5>
                </div>
                <div class="card-body text-center">
                    @if($location->user)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($location->user->first_name . ' ' . $location->user->second_name) }}&background=random&size=100" class="rounded-circle mb-3" alt="Author Avatar">
                        <h5>{{ $location->user->first_name }} {{ $location->user->second_name }}</h5>
                        <p class="text-muted">Учасник з {{ $location->user->created_at->format('F Y') }}</p>
                        {{-- <a href="#" class="btn btn-outline-primary btn-sm">Переглянути профіль</a> --}} {{-- Link to user profile if available --}}
                    @else
                        <img src="https://via.placeholder.com/100/ced4da/ffffff?Text=User" class="rounded-circle mb-3" alt="Author Avatar">
                        <h5>Інформація недоступна</h5>
                        <p class="text-muted">Дані про власника відсутні.</p>
                    @endif
                </div>
            </div>

            @auth
            {{-- Placeholder for Booking/Contact Form Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Зв'язатися з "{{ $location->title }}"</h5>
                </div>
                <div class="card-body">
                    <form id="startChatForm_{{ $location->id }}">
                        <div class="form-group">
                            <label for="initialChatMessage_{{ $location->id }}">Ваше повідомлення</label>
                            <textarea class="form-control" id="initialChatMessage_{{ $location->id }}" name="message" rows="4" required placeholder="Напишіть ваше повідомлення тут..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Надіслати та перейти до чату</button>
                    </form>
                    <div id="startChatError_{{ $location->id }}" class="text-danger mt-2" style="display:none;"></div>
                </div>
            </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const startChatForm = document.getElementById('startChatForm_{{ $location->id }}');
                        const initialMessageInput = document.getElementById('initialChatMessage_{{ $location->id }}');
                        const startChatError = document.getElementById('startChatError_{{ $location->id }}');
                        const locationId = {{ $location->id }};

                        // Ensure jQuery is loaded and setup CSRF token for AJAX
                        if (typeof $ !== 'undefined' && typeof $.ajaxSetup === 'function') {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        } else {
                            console.warn('jQuery not loaded. CSRF token for AJAX for starting chat might not be set up correctly by this script. Ensure jQuery is loaded before this script or handle CSRF token manually if using native fetch.');
                        }

                        if (startChatForm) {
                            startChatForm.addEventListener('submit', function (e) {
                                e.preventDefault();
                                const messageText = initialMessageInput.value.trim();

                                if (messageText === '') {
                                    if(startChatError) {
                                        startChatError.textContent = 'Будь ласка, введіть повідомлення.';
                                        startChatError.style.display = 'block';
                                    }
                                    initialMessageInput.focus();
                                    return;
                                }
                                if(startChatError) {
                                    startChatError.style.display = 'none';
                                }

                                // Disable button to prevent multiple submissions
                                const submitButton = startChatForm.querySelector('button[type="submit"]');
                                const originalButtonText = submitButton.innerHTML;
                                submitButton.disabled = true;
                                submitButton.innerHTML = 'Відправлення...';

                                $.ajax({
                                    url: `/locations/${locationId}/messages`, // Uses existing route from previous setup
                                    method: 'POST',
                                    data: {
                                        message: messageText
                                    },
                                    success: function(response) {
                                        // Message sent successfully
                                        // Redirect to the dedicated chat page for this location
                                        // You will need to define a route like '/chat/location/{locationId}'
                                        window.location.href = `/chat/location/${locationId}`;
                                    },
                                    error: function(xhr) {
                                        console.error('Error sending initial message:', xhr.responseText);
                                        let errorMessage = 'Не вдалося відправити повідомлення. Спробуйте ще раз.';
                                        if (xhr.responseJSON) {
                                            if (xhr.responseJSON.message) {
                                                errorMessage = xhr.responseJSON.message;
                                            }
                                            if (xhr.responseJSON.errors && xhr.responseJSON.errors.message && xhr.responseJSON.errors.message[0]) {
                                                errorMessage = xhr.responseJSON.errors.message[0];
                                            }
                                        }
                                        if(startChatError) {
                                            startChatError.textContent = errorMessage;
                                            startChatError.style.display = 'block';
                                        }
                                        // Re-enable button
                                        submitButton.disabled = false;
                                        submitButton.innerHTML = originalButtonText;
                                    }
                                });
                            });
                        }
                    });
                </script>
            @endauth

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
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                map = new Map(mapElement, {
                    center: { lat: lat, lng: lng },
                    zoom: 15,
                    mapId: '{{env("GOOGLE_MAP_ID", "DEMO_MAP_ID")}}'
                });

                const marker = new AdvancedMarkerElement({
                    map: map,
                    position: { lat: lat, lng: lng },
                    title: "{{ addslashes($location->title) }}"
                });
            } catch (error) {
                console.error("Error initializing map:", error);
                mapElement.innerHTML = "<p class='text-danger p-3'>Помилка при ініціалізації карти. Деталі в консолі розробника.</p>";
            }
        }
        // Ensure initMap is globally accessible if it's called by Google Maps API callback
        // If you are using a callback like `&callback=initMap` in your Google Maps script URL,
        // this function will be called automatically. Otherwise, you might need to call it explicitly:
        // document.addEventListener('DOMContentLoaded', initMap);
        // or if the script is at the end of the body, just initMap();
    </script>
@endif

@include('footer.footer')
<script>
    // Initialize the carousel if it exists
    if (document.getElementById('locationPhotosCarousel')) {
        $('.carousel').carousel();
    }
</script>
</body>
</html>
