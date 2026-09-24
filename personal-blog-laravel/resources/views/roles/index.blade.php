@use('App\Enums\ArticlePermissionEnum')
@use('App\Enums\RolePermissionEnum')

<x-layout title="Manipulate Roles" heading="Manipulate Roles">
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
            @can(RolePermissionEnum::CREATE->value)
                <div class="d-flex justify-content-end px-3 mb-2">
                    <a href="{{ route('roles.create') }}" class="btn btn-outline-primary">
                        + Add Role
                    </a>
                </div>
                @endcan
        </div>

        <ul class="list-group">
            @foreach ($roles as $role)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="text-decoration-none h5 mb-0">
                        {{ $role->name}}
                    </a>

                    <div>
                        @foreach ($role->permissions as $permission)
                            <small class="text-muted me-2">
                                {{ $permission->name }}
                            </small>
                        @endforeach

                        @can(RolePermissionEnum::UPDATE->value)
                            <a class="btn btn-sm btn-outline-secondary me-2" href="{{ route('roles.edit', $role) }}">
                                Edit
                            </a>
                            @endcan

                        @can(RolePermissionEnum::DELETE->value)
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
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
    {{ $roles->links() }}
</x-layout>
