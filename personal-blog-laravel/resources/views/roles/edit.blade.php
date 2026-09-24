<x-layout title="Edit Role" heading="Edit Role">
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="post" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')

            <x-form-input
                name="name"
                label="Name"
                placeholder="Name of Role"
                :value="old('name', $role->name)"
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
                            @checked(
                                in_array(
                                    $permission->name,
                                    old(
                                        'permissions',
                                        $role->permissions->pluck('name')->toArray()
                                    )
                                )
                            )
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
                Update
            </button>
        </form>
    </div>

    <div class="mt-4">
        <a
            href="{{ route('roles.index') }}"
            class="btn btn-outline-secondary btn-sm"
        >
            Go back to dashboard
        </a>
    </div>
</x-layout>
