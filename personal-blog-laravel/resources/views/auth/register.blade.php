<x-layout title="Register" heading="Register">
    <x-slot:headerActions>
        <a href="{{ route('articles.index') }}" class="btn btn-outline-light">Home Dashboard</a>
    </x-slot:headerActions>
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow-sm">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-form-input name="name" label="Name" placeholder="John Doe" />
                <x-form-input name="email" label="Email" type="email" placeholder="email@example.com" />
                <x-form-input name="password" label="Password" type="password" />
                <x-form-input name="password_confirmation" label="Confirm Password" type="password" />

                <button type="submit" class="btn btn-primary">Register</button>

                <div class="link-container">
                    Already had an account? <a href="{{ route('login') }}">[Login here]</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
