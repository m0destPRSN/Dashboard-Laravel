<header class="bg-dark text-white py-2">
    <div class="container-lg d-flex align-items-center">

        {{-- Logo on the left --}}
        <div class="d-flex align-items-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('public/images/logo.png') }}" alt="Logo" class="img-fluid" style="height: 60px;">
            </a>
        </div>

        {{-- Search form in the center --}}
        <form class="form-inline d-flex flex-grow-1 mx-3" action="{{ route('map') }}" method="GET">
            <input
                type="search"
                id="default-search"
                name="query"
                class="form-control mr-2 w-75"
                placeholder="Введіть ключову фразу..."
                value="{{ request('query') }}"
                required
            />
            <button type="submit" class="btn btn-primary">Пошук</button>

        </form>
        {{-- Map button --}}
        <a href="{{ url('/main') }}" class="btn btn-primary mx-1" role="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-browser" viewBox="0 0 16 16">
                <path d="M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3zm2-1a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                <circle cx="3" cy="4" r="0.5"/>
                <circle cx="5" cy="4" r="0.5"/>
                <circle cx="7" cy="4" r="0.5"/>
            </svg>
        </a>

        {{-- Auth buttons on the right --}}
        <div>
            @auth
                <div class="dropdown">
                    <button
                        class="btn btn-link text-white dropdown-toggle d-flex align-items-center"
                        type="button"
                        id="userDropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        style="text-decoration: none;"
                    >
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M12 14c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/>
                        </svg>
                        {{ auth()->user()->name ?? '' }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-center" aria-labelledby="userDropdown" style="min-width: 220px; background-color: #343a40; transform: translate3d(-65px, 44px, 0px) !important;">
                        <div class="px-3 py-2 text-center">
                            <div class="mb-2">
                                <div class="text-white" style="font-size: 0.95em;">{{ auth()->user()->phone }}</div>
                            </div>
                            <div class="mb-2 d-flex justify-content-center">
                                <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.2); background: #000; display: flex; align-items: center; justify-content: center;">
                                    <svg width="100%" height="100%" viewBox="0 0 24 24" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="8" r="4" fill="#fff"/>
                                        <path d="M12 14c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z" fill="#fff"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2 text-white" style="font-size: 1em;">
                                {{ auth()->user()->first_name ?? '' }} {{ auth()->user()->second_name ?? '' }}
                            </div>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-block mx-auto" style="width: 100%;">Вийти</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-link text-white">Увійти</a>
            @endauth
        </div>
        <a href="{{url('locations/create')}}" class="btn btn-primary" role="button" style:="" style="
        position: absolute;
    left: 97%;
">
            +
        </a>
    </div>

</header>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownButton = document.getElementById('userDropdown');
        const dropdownMenu = document.querySelector('#userDropdown + .dropdown-menu');

        dropdownButton.addEventListener('click', function () {
            dropdownMenu.style.opacity = '0'; // приховуємо меню
            setTimeout(() => {
                dropdownMenu.style.transform = 'translate3d(-65px, 44px, 0px)';
                dropdownMenu.style.position = 'absolute';
                dropdownMenu.style.opacity = '1'; // плавно показуємо меню
                dropdownMenu.style.transition = 'opacity 0.15s ease-in-out';
            }, 10); // 10ms затримки для рендера Bootstrap
        });
    });
</script>

