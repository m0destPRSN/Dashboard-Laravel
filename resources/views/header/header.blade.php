<header class="bg-dark text-white py-2">
    <div class="container-lg d-flex align-items-center">

        {{-- Logo --}}
        <div class="d-flex align-items-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('public/images/logo.png') }}" alt="Logo" class="img-fluid" style="height: 60px;">
            </a>
        </div>

        {{-- Search form --}}
        @php
            $icon = $icon ?? 'map';
            $iconLink = $iconLink ?? url('/map');
            $query = $query ?? request('query');
            $searchAction = ($icon === 'browser') ? url('map/search') : route('search');
        @endphp

        <form class="form-inline d-flex flex-grow-1 mx-3" action="{{ $searchAction }}" method="GET">
            <input
                type="search"
                name="query"
                class="form-control mr-2 w-75"
                placeholder="Введіть ключову фразу..."
                value="{{ $query }}"
                required
            />
            <button type="submit" class="btn btn-primary">Пошук</button>
        </form>

        {{-- Spacer --}}

        {{-- Map button --}}

        {{-- Conditional icon button --}}
        @php
            $currentQuery = request()->getQueryString();
            $targetIcon = $icon ?? 'map';
            $currentPath = rtrim(request()->path(), '/');

            if ($targetIcon === 'browser') {
                // Иконка браузера
                if (str_starts_with($currentPath, 'map/search') && $currentQuery) {
                    // На /map/search?query → /search?query
                    $iconLink = url('search') . '?' . $currentQuery;
                } elseif ($currentPath === 'map' || $currentPath === '') {
                    // На /map или корне / → /main
                    $iconLink = url('main');
                } else {
                    // В остальных случаях → /search с query (если есть)
                    $iconLink = url('search') . ($currentQuery ? '?' . $currentQuery : '');
                }
            } elseif ($targetIcon === 'map') {
                // Иконка карты
                if (str_starts_with($currentPath, 'search') && $currentQuery) {
                    // На /search?query → /map/search?query
                    $iconLink = url('map/search') . '?' . $currentQuery;
                } elseif ($currentPath === 'map' || $currentPath === 'main' || $currentPath === '') {
                    // На /map, /main или корне / → /main (если на карте) или /map (если не на карте)
                    $iconLink = ($currentPath === 'map' || $currentPath === '') ? url('main') : url('map');
                } else {
                    // По умолчанию → /map
                    $iconLink = url('map');
                }
            } else {
                $iconLink = url('map');
            }
        @endphp

        <a href="{{ $iconLink }}" class="btn btn-primary mx-1" role="button">
            @if(($icon ?? 'map') === 'map')
                {{-- Иконка карты --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
                    <path d="M15.817.113A.5.5 0 0 0 15.5 0a.5.5 0 0 0-.16.027l-4.857 1.619-5.026-1.61a.5.5 0 0 0-.316 0l-5 1.5A.5.5 0 0 0 0 2v12a.5.5 0 0 0 .683.474l4.857-1.619 5.026 1.61a.5.5 0 0 0 .316 0l5-1.5A.5.5 0 0 0 16 14V2a.5.5 0 0 0-.183-.387zM6 2.434v10.132l-4 1.333V3.767l4-1.333zm1 10.132V2.434l4-1.333v10.132l-4 1.333zm5-1.333-4 1.333V3.767l4-1.333v8.799z"/>
                </svg>
            @elseif($icon === 'browser')
                {{-- Иконка браузера --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-browser" viewBox="0 0 16 16">
                    <path d="M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3zm2-1a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                    <circle cx="3" cy="4" r="0.5"/>
                    <circle cx="5" cy="4" r="0.5"/>
                    <circle cx="7" cy="4" r="0.5"/>
                </svg>
            @endif
        </a>


        {{-- Auth section --}}
        <div>
            @auth
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle d-flex align-items-center" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M12 14c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/>
                        </svg>
                        {{ auth()->user()->name ?? '' }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-center" aria-labelledby="userDropdown" style="min-width: 220px; background-color: #343a40; transform: translate3d(-65px, 44px, 0px) !important;">
                        <div class="px-3 py-2 text-center">
                            <div class="text-white">{{ auth()->user()->phone }}</div>
                            <div class="my-2 d-flex justify-content-center">
                                <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center;">
                                    <svg width="100%" height="100%" viewBox="0 0 24 24" fill="#343a40">
                                        <circle cx="12" cy="8" r="4" fill="#fff"/>
                                        <path d="M12 14c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z" fill="#fff"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-white">{{ auth()->user()->first_name }} {{ auth()->user()->second_name }}</div>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button type="submit" class="btn btn-danger btn-block mt-3">Вийти</button>
                            </form>
                            <div class="dropdown-divider mt-3"></div>
                            <ul class="list-unstyled mt-3" style="min-width: 250px;">
                                <li class="nav-item mt-2">
                                    <a href="{{ route('locations.my') }}" class="text-white">Мої локації</a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="text-white" href="{{ route('chat.list') }}">Повідомлення</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-link text-white">Увійти</a>
            @endauth
        </div>

        <a href="{{ url('locations/create') }}" class="btn btn-primary" style="position: absolute; left: 97%;">+</a>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownButton = document.getElementById('userDropdown');
        const dropdownMenu = document.querySelector('#userDropdown + .dropdown-menu');
        dropdownButton.addEventListener('click', function () {
            dropdownMenu.style.opacity = '0';
            setTimeout(() => {
                dropdownMenu.style.transform = 'translate3d(-65px, 44px, 0px)';
                dropdownMenu.style.position = 'absolute';
                dropdownMenu.style.opacity = '1';
                dropdownMenu.style.transition = 'opacity 0.15s ease-in-out';
            }, 10);
        });
    });
</script>
