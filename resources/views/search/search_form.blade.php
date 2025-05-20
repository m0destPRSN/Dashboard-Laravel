<div class="container my-2" style="max-width: 900px;">
    <form class="d-flex justify-content-center align-items-center gap-2" action="{{ route('search') }}" method="POST">
        @csrf
        <input type="search" id="default-search" name="query" class="form-control w-100" placeholder="Введіть ключову фразу..." required />
        <button type="submit" class="btn btn-primary ">Search</button>
    </form>
</div>
