@use('App\Enums\UserPermissionEnum')
@use('App\Enums\ArticlePermissionEnum')
@use('App\Enums\RolePermissionEnum')

<x-layout title="Admin Dashboard" heading="Admin Dashboard">
    <x-slot:headerActions>
        <div class="link-container">
            @can(UserPermissionEnum::READ->value)
                <a href="{{ route('users.index') }}" class="btn btn-outline-light">Manipulate Users</a>
            @endcan
            @can(RolePermissionEnum::READ->value)
                <a href="{{ route('roles.index') }}" class="btn btn-outline-light">Manipulate Roles</a>
            @endcan
            <a href="{{ route('articles.index') }}" class="btn btn-outline-light">Return to Home</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light">Logout</button>
            </form>
        </div>
    </x-slot:headerActions>

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
        @can(ArticlePermissionEnum::CREATE->value)
        <div class="d-flex justify-content-end px-3 mb-2">
            <a href="{{ route('articles.create') }}" class="btn btn-outline-primary">
                + Add Article
            </a>
        </div>
        @endcan

        <ul class="list-group">
            @foreach ($articles as $article)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="text-decoration-none h5 mb-0" href="{{ route('articles.show', $article) }}">
                        {{ $article->title }}
                    </a>

                    <div>
                        <small class="text-muted me-2">
                            {{ date_format($article->dop, 'M j, Y') }}
                        </small>

                        @can(ArticlePermissionEnum::UPDATE->value)
                            <a class="btn btn-sm btn-outline-secondary me-2" href="{{ route('articles.edit', $article) }}">
                                Edit
                            </a>
                        @endcan

                        @can(ArticlePermissionEnum::DELETE->value)
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>

                </li>
                @endforeach
        </ul>
    </nav>
    <!-- Pagination Controls -->
    {{ $articles->links() }}
</x-layout>
