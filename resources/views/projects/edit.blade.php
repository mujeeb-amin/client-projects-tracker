
@extends('layouts.app')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Update Project Information</h5>
            </div>

            <div class="card-body p-4">

                <form action="/projects/{{ $project->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Client -->
                        <div class="col-md-6">
                            <label for="client_id" class="form-label">
                                Client
                            </label>

                            <select
                                id="client_id"
                                name="client_id"
                                class="form-select @error('client_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Client</option>

                                @foreach($clients as $client)
                                    <option
                                        value="{{ $client->id }}"
                                        @selected(old('client_id', $project->client_id) == $client->id)
                                    >
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('client_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Project Title -->
                        <div class="col-md-6">
                            <label for="title" class="form-label">
                                Project Title
                            </label>

                            <input
                                id="title"
                                type="text"
                                name="title"
                                value="{{ old('title', $project->title) }}"
                                class="form-control @error('title') is-invalid @enderror"
                                required
                            >

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Agreed Price -->
                        <div class="col-md-6">
                            <label for="agreed_price" class="form-label">
                                Agreed Price ($)
                            </label>

                            <input
                                id="agreed_price"
                                type="number"
                                name="agreed_price"
                                step="0.01"
                                min="0"
                                value="{{ old('agreed_price', $project->agreed_price) }}"
                                class="form-control @error('agreed_price') is-invalid @enderror"
                                required
                            >

                            @error('agreed_price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="col-md-6">
                            <label for="deadline" class="form-label">
                                Deadline
                            </label>

                            <input
                                id="deadline"
                                type="date"
                                name="deadline"
                                value="{{ old('deadline', $project->deadline) }}"
                                class="form-control @error('deadline') is-invalid @enderror"
                                required
                            >

                            @error('deadline')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >
                                <option value="ongoing"
                                    @selected(old('status', $project->status) == 'ongoing')>
                                    Ongoing
                                </option>

                                <option value="completed"
                                    @selected(old('status', $project->status) == 'completed')>
                                    Completed
                                </option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="/projects" class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-success">
                            Update Project
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
