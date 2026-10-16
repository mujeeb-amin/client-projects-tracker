
@extends('layouts.app')

@section('title', 'Edit Client')

@section('page-title', 'Edit Client')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Update Client Information</h5>
            </div>

            <div class="card-body p-4">

                <form action="/clients/{{ $client->id }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $client->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $client->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror"
                            >

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $client->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Company -->
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>

                            <input
                                type="text"
                                name="company_name"
                                value="{{ old('company_name', $client->company_name) }}"
                                class="form-control @error('company_name') is-invalid @enderror"
                            >

                            @error('company_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label">Address</label>

                            <textarea
                                name="address"
                                rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                            >{{ old('address', $client->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="/clients"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-success">
                            Update Client
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
