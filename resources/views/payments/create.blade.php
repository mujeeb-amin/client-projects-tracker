
@extends('layouts.app')

@section('title', 'Add Payment')
@section('page-title', 'Add New Payment')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Payment Information</h5>
            </div>

            <div class="card-body p-4">

                <form action="/payments" method="POST">
                    @csrf

                    <div class="row g-3">

                        <!-- Project -->
                        <div class="col-md-6">
                            <label for="project_id" class="form-label">
                                Project
                            </label>

                            <select
                                id="project_id"
                                name="project_id"
                                class="form-select @error('project_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Project</option>

                                @foreach($projects as $project)
                                    <option
                                        value="{{ $project->id }}"
                                        @selected(old('project_id') == $project->id)
                                    >
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            </select>

                            @error('project_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Task -->
                        <div class="col-md-6">
                            <label for="task" class="form-label">
                                Task
                            </label>

                            <input
                                id="task"
                                type="text"
                                name="task"
                                value="{{ old('task') }}"
                                class="form-control @error('task') is-invalid @enderror"
                                placeholder="e.g. First installment"
                            >

                            @error('task')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <label for="amount" class="form-label">
                                Amount ($)
                            </label>

                            <input
                                id="amount"
                                type="number"
                                name="amount"
                                step="0.01"
                                min="0.01"
                                value="{{ old('amount') }}"
                                class="form-control @error('amount') is-invalid @enderror"
                                required
                            >

                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="col-md-6">
                            <label for="payment_date" class="form-label">
                                Payment Date
                            </label>

                            <input
                                id="payment_date"
                                type="date"
                                name="payment_date"
                                value="{{ old('payment_date', date('Y-m-d')) }}"
                                class="form-control @error('payment_date') is-invalid @enderror"
                                required
                            >

                            @error('payment_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div class="col-12">
                            <label for="note" class="form-label">
                                Note
                            </label>

                            <textarea
                                id="note"
                                name="note"
                                rows="3"
                                class="form-control @error('note') is-invalid @enderror"
                                placeholder="Optional payment note"
                            >{{ old('note') }}</textarea>

                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="/payments" class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Save Payment
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
