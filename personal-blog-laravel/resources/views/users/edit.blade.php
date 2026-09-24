<x-layout title="Update User" heading="Update User">
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <x-form-input name="name" label="Name" :value="$user->name" />
            <x-form-input name="email" label="Email" type="email" :value="$user->email" />
            <x-form-input name="password" label="Password" type="password" />

            <div class="form-group">
                <label for="role">Role</label>

                <select name="role" id="role" class="form-control border-dark" required>
                    @foreach ($roles as $role)
                        <option
                            value="{{ $role->name }}"
                            {{ old('role', $user->roles->pluck('name')->first()) === $role->name ? 'selected' : '' }}
                        >
                            {{ $role->name }}
                        </option>
                        @endforeach
                </select>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <div class="mt-4">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Go back to dashboard</a>
    </div>
</x-layout>
