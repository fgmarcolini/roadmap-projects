@use('App\Enums\UserPermissionEnum')
@use('App\Enums\ArticlePermissionEnum')
@use('App\Enums\RolePermissionEnum')

<x-layout title="Manipulate Users" heading="Manipulate Users">
    <x-slot:headerActions>
        <div class="link-container">
            @can(ArticlePermissionEnum::VIEW_ADMIN)
                <a href="{{ route('articles.admin') }}" class="btn btn-outline-light">Return to Admin</a>
            @endcan
            @can(ArticlePermissionEnum::VIEW_HOME)
                <a href="{{ route('articles.index') }}" class="btn btn-outline-light">Return to Home</a>
            @endcan
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light">Logout</button>
            </form>
        </div>
    </x-slot:headerActions>

    <nav>
        <div>
            @can(UserPermissionEnum::CREATE->value)
                <div class="d-flex justify-content-end px-3 mb-2">
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                        + Add User
                    </a>
                </div>
            @endcan
        </div>

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
