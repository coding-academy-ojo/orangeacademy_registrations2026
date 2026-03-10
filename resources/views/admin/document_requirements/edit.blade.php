@extends('layouts.admin')
@section('page-title', 'Edit Document Requirement')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.document_requirements.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card max-w-2xl">
        <div class="card-body p-4">
            <form action="{{ route('admin.document_requirements.update', $documentRequirement) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Requirement Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $documentRequirement->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="3">{{ old('description', $documentRequirement->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_required" name="is_required"
                        value="1" {{ old('is_required', $documentRequirement->is_required) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_required">Mandatory Document?</label>
                    <div class="form-text">If checked, students cannot complete the registration without uploading this
                        file.</div>
                </div>

                <div class="text-end border-top pt-3">
                    <button type="submit" class="btn btn-orange px-4">Update Requirement</button>
                </div>
            </form>
        </div>
    </div>
@endsection