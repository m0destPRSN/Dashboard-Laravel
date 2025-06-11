@include('head.head_doc')

{{-- Додайте ці стилі у ваш head_doc або в окремий CSS файл --}}
<style>
    .post-card-link {
        color: inherit; /* Успадковує колір тексту від батьківського елемента */
        display: block; /* Робить весь блок посилання клікабельним */
        text-decoration: none; /* Прибирає підкреслення посилання */
    }

    .post-card-link:hover {
        text-decoration: none; /* Прибирає підкреслення при наведенні */
        color: inherit;
    }

    .custom-post-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 1px solid #e9ecef; /* Світла рамка, схожа на приклад */
        /* Якщо ви хочете помаранчеву рамку, як натякає приклад: */
        /* border-color: #FFA500; */
    }

    .custom-post-card:hover {
        transform: translateY(-5px); /* Легкий підйом картки при наведенні */
        box-shadow: 0 8px 16px rgba(0,0,0,0.15); /* Тінь для виділення */
    }

    .custom-post-card .card-img-top {
        width: 100%;
        max-height: 200px; /* Обмеження висоти зображення */
        object-fit: cover; /* Масштабує зображення, щоб воно заповнило контейнер, обрізаючи зайве */
    }

    .card-img-top-placeholder {
        height: 200px; /* Та сама висота, що й для зображень */
        background-color: #f8f9fa; /* Світлий фон для заповнювача */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d; /* Колір тексту заповнювача */
        font-size: 0.9rem;
    }

    .custom-post-card .card-body {
        padding: 1.25rem; /* Стандартний padding Bootstrap */
    }

    .custom-post-card .card-title {
        margin-bottom: 0; /* Прибирає нижній відступ у заголовка, оскільки це останній елемент */
        font-size: 1.1rem; /* Трохи більший розмір шрифту для заголовка */
        /* Якщо ви хочете помаранчевий колір тексту, як натякає приклад: */
        /* color: #FFA500; */
    }
</style>

<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map'), 'query' => request('query')])

<div class="container mt-4">
    <div class="row justify-content-center">
        @forelse($posts as $post)
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <a href="{{ url($post->link) }}" class="post-card-link w-100">
                    <div class="card h-100 custom-post-card">
                        @if($post->photo_path)
                            <img src="{{ asset('storage/' . $post->photo_path) }}" class="card-img-top" alt="{{ $post->name }}">
                        @else
                            <div class="card-img-top-placeholder">
                                <span>No image available</span>
                            </div>
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $post->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col">
                <p class="text-center text-muted">No posts found.</p>
            </div>
        @endforelse
    </div>
</div>

@include('footer.footer')
</body>
</html>
