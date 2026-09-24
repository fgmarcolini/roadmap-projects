<x-layout title="New Role" heading="New Role">
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="post" action="{{ route('roles.store') }}">
            @csrf

            <x-form-input
                name="name"
                label="Name"
                placeholder="Name of Role"
            />

            <div class="mt-3">
                <label class="form-label">Permissions</label>

                @foreach ($permissions as $permission)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->name }}"
                            id="perm-{{ Str::slug($permission->name) }}"
                            @checked(in_array($permission->name, old('permissions', [])))
                        >

                        <label
                            class="form-check-label"
                            for="perm-{{ Str::slug($permission->name) }}"
                        >
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Create
            </button>
        </form>
    </div>

    <div class="mt-4">
        <a
            href="{{ route('users.index') }}"
            class="btn btn-outline-secondary btn-sm"
        >
            Go back to dashboard
        </a>
    </div>
</x-layout>
