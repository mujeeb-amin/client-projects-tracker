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

                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >

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