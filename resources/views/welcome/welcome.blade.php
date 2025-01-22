@include('head.head_doc')
<body class="bg-light">

<header class="bg-dark text-white py-3">
    <div class="container d-flex justify-content-between align-items-center">
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-link text-white">Home</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link text-white">Log in</a>
                @endauth
            </div>
        @endif
    </div>

</header>

    @include('search.search_form')

</body>
</html>
