<x-layout title="New User" heading="New User">
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="post" action="{{ route('users.store') }}">
            @csrf

            <x-form-input name="name" label="Name" placeholder="Name of User" />
            <x-form-input name="email" label="Email" type="email" />
            <x-form-input name="password" label="Password" type="password" />

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control border-dark" required>
                    <option value="">-- Choose a Role--</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role_name') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                </select>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
    <div class="mt-4">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Go back to dashboard</a>
    </div>
</x-layout>
