<x-layout title="Admin Dashboard" heading="Admin Dashboard">
    <x-slot:headerActions>
        <a href="{{ route('articles.create') }}" class="btn btn-light">+ Add Article</a>
    </x-slot:headerActions>

    <div class="mb-3">
        <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">Return to Home</a>
    </div>

    <div class="mb-4">
        <form action="{{ route('articles.admin') }}" method="get" class="row g-3">
            <div class="col-auto">
                <input type="text" name="title" class="form-control" placeholder="Filter by title...">
            </div>
            <div class="col-auto">
                <input type="date" name="dop" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('articles.admin') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <nav>
        <ul class="list-group">
            @foreach ($articles as $article)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="text-decoration-none h5 mb-0"
                        href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                    @can('update', $article)
                        <div>
                            <a class="btn btn-sm btn-outline-secondary me-2"
                                href="{{ route('articles.edit', $article) }}">Edit</a>
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    @endcan
                </li>
            @endforeach
        </ul>
    </nav>
    <!-- Pagination Controls -->
    {{ $articles->links() }}
</x-layout>
