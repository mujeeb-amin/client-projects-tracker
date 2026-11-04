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

                @error('password')
                    <div class="invalid-feedback">
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

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                >

                @error('password_confirmation')
                    <div class="invalid-feedback">
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