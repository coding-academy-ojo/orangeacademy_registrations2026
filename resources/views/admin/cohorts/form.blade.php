@extends('layouts.admin')
@section('page-title', isset($cohort) ? 'Edit Cohort' : 'New Cohort')
@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ isset($cohort) ? route('admin.cohorts.update', $cohort) : route('admin.cohorts.store') }}">
                @csrf
                @if(isset($cohort)) @method('PUT') @endif
                <div class="mb-3">
                    <label class="form-label fw-semibold">Academy <span class="text-danger">*</span></label>
                    <select name="academy_id" class="form-select" required>
                        <option value="">Select...</option>
                        @foreach($academies as $a)
                            <option value="{{ $a->id }}" {{ old('academy_id', $cohort->academy_id ?? '') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cohort Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $cohort->name ?? '') }}" class="form-control"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control"
                        rows="3">{{ old('description', $cohort->description ?? '') }}</textarea>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', isset($cohort) ? $cohort->start_date->format('Y-m-d') : '') }}"
                            class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date"
                            value="{{ old('end_date', isset($cohort) ? $cohort->end_date->format('Y-m-d') : '') }}"
                            class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['planning', 'active', 'completed', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $cohort->status ?? 'planning') == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">{{ isset($cohort) ? 'Update' : 'Create' }}</button>
                    <a href="{{ route('admin.cohorts') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection