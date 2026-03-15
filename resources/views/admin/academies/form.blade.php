@extends('layouts.admin')
@section('page-title', isset($academy) ? 'Edit Academy' : 'New Academy')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    
    <div class="card" style="max-width:800px;">
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
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Registration Rules (Shown to student on Step 4)</label>
                    <div id="editor" style="height: 200px;">{!! old('registration_rules', $academy->registration_rules ?? '') !!}</div>
                    <input type="hidden" name="registration_rules" id="registration_rules">
                    <div class="form-text">Format your registration rules with fonts, colors, and styling. These will be shown to users when they select this academy.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange" onclick="saveQuillContent()">{{ isset($academy) ? 'Update' : 'Create' }}
                        Academy</button>
                    <a href="{{ route('admin.academies') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            },
            placeholder: 'Enter registration rules, conditions, or instructions...'
        });

        function saveQuillContent() {
            document.getElementById('registration_rules').value = quill.root.innerHTML;
        }

        // Auto-save on form submit
        document.querySelector('form').addEventListener('submit', saveQuillContent);
    </script>
@endsection