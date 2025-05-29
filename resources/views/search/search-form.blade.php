<form class="form-inline d-flex flex-grow-1 mx-3" action="{{ $action }}" method="GET">
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
