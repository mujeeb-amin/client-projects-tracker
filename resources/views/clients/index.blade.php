
@extends('layouts.app')

@section('title', 'Clients Management')

@section('page-title', 'Clients Management')

@section('content')

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h5 class="mb-1">Clients List</h5>
        <p class="text-muted mb-0">
            Manage all your clients
        </p>
    </div>

    <a href="/clients/create" class="btn btn-primary">
        + Add Client
    </a>

</div>

<!-- Clients Table -->
<div class="card border-0 shadow-sm rounded-3">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($clients as $client)

                        <tr>
                            <td>{{ $client->id }}</td>

                            <td class="fw-semibold">
                                {{ $client->name }}
                            </td>

                            <td>{{ $client->phone }}</td>

                            <td>{{ $client->email }}</td>

                            <td>{{ $client->company_name }}</td>

                            <td>{{ $client->address }}</td>

                            <td>
                                <div class="d-flex gap-2">

                                    <!-- Edit -->
                                    <a href="/clients/{{ $client->id }}/edit"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <!-- Delete -->
                                    <form
                                        action="/clients/{{ $client->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this client?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-4">
                                No clients found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
