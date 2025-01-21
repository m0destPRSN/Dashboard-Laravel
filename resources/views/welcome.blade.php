<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
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

<div class="container py-5">
    <form class="d-flex justify-content-center mb-4" action="{{ route('search') }}" method="POST">
        <input type="search" id="default-search" name="query" class="form-control w-75 mr-2" placeholder="Search Mockups, Logos..." required />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

</div>

</body>
</html>
