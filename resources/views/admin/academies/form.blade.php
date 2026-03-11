@extends('layouts.admin')
@section('page-title', isset($academy) ? 'Edit Academy' : 'New Academy')
@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data"
                action="{{ isset($academy) ? route('admin.academies.update', $academy) : route('admin.academies.store') }}">
                @csrf
                @if(isset($academy)) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Academy Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $academy->name ?? '') }}" class="form-control"
                        required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" value="{{ old('location', $academy->location ?? '') }}"
                            class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Location Link (Google Maps URL)</label>
                        <input type="url" name="location_link"
                            value="{{ old('location_link', $academy->location_link ?? '') }}" class="form-control"
                            placeholder="https://maps.google.com/...">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Code</label>
                        <input type="text" name="code" value="{{ old('code', $academy->code ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Academy Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if(isset($academy) && $academy->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $academy->image) }}" alt="Academy Image" class="img-thumbnail"
                                    style="max-height: 80px;">
                            </div>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Registration Rules (Shown to student on Step 4)</label>
                            <textarea name="registration_rules" class="form-control" rows="4"
                                placeholder="Enter registration rules, conditions, or instructions specific to this academy...">{{ old('registration_rules', $academy->registration_rules ?? '') }}</textarea>
                            <div class="form-text">You can use basic line breaks. These will be shown to users when they
                                select this academy.</div>
                        </div>
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