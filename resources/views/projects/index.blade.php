
@extends('layouts.app')

@section('title', 'Projects Management')

@section('page-title', 'Projects Management')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h5 class="mb-1">Projects List</h5>
        <p class="text-muted mb-0">
            Manage and track your projects
        </p>
    </div>

    <a href="/projects/create" class="btn btn-primary">
        + Add Project
    </a>

</div>

<!-- Projects Table -->
<div class="card border-0 shadow-sm rounded-3">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Project Title</th>
                        <th>Agreed Price</th>
                        <th>Received</th>
                        <th>Remaining</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($projects as $project)

                        <tr>
                            <td>{{ $project->id }}</td>

                            <td>{{ $project->client->name ?? 'N/A' }}</td>

                            <td class="fw-semibold">
                                {{ $project->title }}
                            </td>

                            <td>
                                ${{ number_format($project->agreed_price, 2) }}
                            </td>

                            <td class="text-success fw-semibold">
                                ${{ number_format($project->payments_sum_amount ?? 0, 2) }}
                            </td>

                            <td class="text-danger fw-semibold">
                                ${{ number_format(
                                    $project->agreed_price - ($project->payments_sum_amount ?? 0),
                                    2
                                ) }}
                            </td>

                            <td>{{ $project->deadline }}</td>

                            <td>
                                @if($project->status == 'completed')
                                    <span class="badge bg-success">
                                        Completed
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Ongoing
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a href="/projects/{{ $project->id }}/edit"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form
                                        action="/projects/{{ $project->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this project?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9"
                                class="text-center text-muted py-4">
                                No projects found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
