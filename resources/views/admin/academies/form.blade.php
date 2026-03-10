@extends('layouts.admin')
@section('page-title', isset($academy) ? 'Edit Academy' : 'New Academy')
@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ isset($academy) ? route('admin.academies.update', $academy) : route('admin.academies.store') }}">
                @csrf
                @if(isset($academy)) @method('PUT') @endif
                <div class="mb-3">
                    <label class="form-label fw-semibold">Academy Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $academy->name ?? '') }}" class="form-control"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" value="{{ old('location', $academy->location ?? '') }}"
                        class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Code</label>
                    <input type="text" name="code" value="{{ old('code', $academy->code ?? '') }}" class="form-control">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">{{ isset($academy) ? 'Update' : 'Create' }}
                        Academy</button>
                    <a href="{{ route('admin.academies') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection