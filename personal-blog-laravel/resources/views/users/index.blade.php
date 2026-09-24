@use('App\Enums\UserPermissionEnum')
@use('App\Enums\ArticlePermissionEnum')

<x-layout title="Manipulate Users" heading="Manipulate Users">
    <x-slot:headerActions>
        <div class="link-container">
            <a href="{{ route('articles.admin') }}" class="btn btn-outline-light">Return to Admin</a>
            <a href="{{ route('articles.index') }}" class="btn btn-outline-light">Return to Home</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light">Logout</button>
            </form>
        </div>
    </x-slot:headerActions>

    <div class="mb-4">
        <form action="{{ route('users.index') }}" method="get" class="row g-3">
            <div class="col-auto">
                <input type="text" name="name" class="form-control" placeholder="Filter by name...">
            </div>
            <div class="col-auto">
                <input type="text" name="email" class="form-control" placeholder="Filter by email...">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <nav>
        @can(UserPermissionEnum::CREATE->value)
        <div class="d-flex justify-content-end px-3 mb-2">
            <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                + Add User
            </a>
        </div>
        @endcan

        <ul class="list-group">
            @foreach ($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="text-decoration-none h5 mb-0">
                        {{ $user->email }}
                    </a>

                    <div>
                        @can(UserPermissionEnum::UPDATE->value)
                            <a class="btn btn-sm btn-outline-secondary me-2" href="{{ route('users.edit', $user) }}">
                                Edit
                            </a>
                        @endcan

                        @can(UserPermissionEnum::DELETE->value)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
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
    {{ $users->links() }}
</x-layout>
