<x-layout title="Home" heading="Personal Blog">
    <x-slot:headerActions>
        @auth
            <div class="link-container">
                <a href="{{ route('articles.admin') }}" class="btn btn-outline-light">Admin Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        @else
            <div class="link-container">
                <a href="{{ route('login') }}" class="btn btn-outline-light">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light">Register</a>
            </div>
        @endauth
    </x-slot:headerActions>

    <div class="container mb-4">
        <form action="{{ route('articles.index') }}" method="get" class="row g-3">
            <div class="col-auto">
                <input type="text" name="title" class="form-control" placeholder="Filter by title...">
            </div>
            <div class="col-auto">
                <input type="date" name="dop" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="list-group">
        @foreach ($articles as $article)
            <a href="{{ route('articles.show', $article) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <h5 class="mb-1">{{ $article->title }}</h5>
                <small class="text-muted">{{ date_format($article->dop, 'M j, Y') }}</small>
            </a>
        @endforeach
    </div>

    <!-- Pagination Controls -->
    {{ $articles->links() }}
</x-layout>
