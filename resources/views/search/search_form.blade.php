
<div class="container py-5">
    <form class="d-flex justify-content-center mb-4" action="{{ route('search') }}" method="POST">
        @csrf
        <input type="search" id="default-search" name="query" class="form-control w-75 mr-2" placeholder="Введіть ключову фразу..." required />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

</div>
