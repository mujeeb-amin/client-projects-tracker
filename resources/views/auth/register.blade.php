@extends('layouts.guest')

@section('title', 'Sign Up')

@section('content')

<div class="register">

    <div class="register-content">

        <form action="{{ route('register.submit') }}"
              method="POST">

            @csrf

            <h1 class="text-center mb-2">
                Sign Up
            </h1>

            <p class="text-muted text-center mb-3">
                Create your Client Tracker account
            </p>

            {{-- General Errors --}}
            @if ($errors->any())

                <div class="alert alert-danger">
                    Please check the information below.
                </div>

            @endif

            {{-- Name --}}
            <div class="mb-2">

                <label for="name" class="form-label">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    required
                    autocomplete="name"
                    placeholder="Enter your name"
                >

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Email --}}
            <div class="mb-2">

                <label for="email" class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                    autocomplete="username"
                    placeholder="you@example.com"
                >

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Password --}}
            <div class="mb-2">

                <label for="password" class="form-label">
                    Password
                </label>

                <div class="password-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Enter password"
                    >
                    <button data-toggle-password="password" type="button" class="btn password-toggle"
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

                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Confirm Password --}}
            <div class="mb-2">

                <label for="password_confirmation"
                       class="form-label">
                    Confirm Password
                </label>

                <div class="password-field">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    >
                    <button data-toggle-password="password_confirmation" type="button" class="btn password-toggle"
                            aria-controls="password_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/>
                            <circle cx="12" cy="12" r="2.5"/>
                            <path class="password-eye-slash" d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>

                @error('password_confirmation')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Register Button --}}
            <button
                type="submit"
                class="btn btn-theme d-block w-100 fw-500 mb-2"
            >
                Sign Up
            </button>

            {{-- Login Link --}}
            <div class="text-center text-muted mt-2">

                Already registered?

                <a href="{{ route('login') }}">
                    Sign In
                </a>

            </div>

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
document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
    const input = document.getElementById(button.dataset.togglePassword);

    button.addEventListener('click', function () {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(show));
    });
});
</script>
@endpush
