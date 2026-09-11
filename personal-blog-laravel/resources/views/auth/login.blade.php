<x-layout title="Login" heading="Login">
    <x-slot:headerActions>
        <a href="{{ route('articles.index') }}" class="btn btn-outline-light">Home Dashboard</a>
    </x-slot:headerActions>
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow-sm">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <x-form-input name="email" label="Email" type="email" placeholder="email@example.com" />
                <x-form-input name="password" label="Password" type="password" />

                <button type="submit" class="btn btn-primary">Login</button>

                <div class="link-container">
                    Don't have an account? <a href="{{ route('register') }}">[Register here]</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
