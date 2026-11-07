@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')

<div class="login">

    <div class="login-content">

        <form method="POST"
              action="{{ route('login.submit') }}">

            @csrf

            <h1 class="text-center mb-2">
                Sign In
            </h1>

            <p class="text-muted text-center mb-3">
                Client Projects Tracker
            </p>

            {{-- Error Messages --}}
            @if ($errors->any())

                <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                </div>

            @endif

            {{-- Email --}}
            <div class="mb-3">

                <label class="form-label" for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                    autocomplete="username"
                    autofocus
                    placeholder="you@example.com"
                >

            </div>

            {{-- Password --}}
            <div class="mb-3">

                <label class="form-label" for="password">
                    Password
                </label>

                <div class="password-field">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >
                    <button id="loginPasswordToggle" type="button" class="btn password-toggle"
                            aria-controls="password" aria-label="Show password" aria-pressed="false">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/>
                            <circle cx="12" cy="12" r="2.5"/>
                            <path class="password-eye-slash" d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>

            </div>

            {{-- Login Button --}}
            <button
                type="submit"
                class="btn btn-theme d-block w-100 fw-500 mb-2"
            >
                Sign In
            </button>

            {{-- Register Link --}}
            @if (Route::has('register'))

                <div class="text-center text-muted mt-2">

                    Don't have an account?

                    <a href="{{ route('register') }}">
                        Sign Up
                    </a>

                </div>

            @endif

        </form>

    </div>

</div>

@endsection

@push('styles')
<style>
.password-field {
    position: relative;
}
.password-field .form-control {
    padding-inline-end: 3rem;
}
.password-toggle {
    position: absolute;
    inset-inline-end: 0.25rem;
    top: 50%;
    transform: translateY(-50%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2rem;
    padding: 0;
    color: #475569;
}
.password-toggle .password-eye-slash {
    display: none;
}
.password-toggle[aria-pressed="true"] .password-eye-slash {
    display: inline;
}
.password-field .form-control.is-invalid {
    padding-inline-end: 4.5rem;
    background-position: right 3rem center;
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    const input = document.getElementById('password');
    const toggle = document.getElementById('loginPasswordToggle');

    toggle.addEventListener('click', function () {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(show));
    });
})();
</script>
@endpush
    