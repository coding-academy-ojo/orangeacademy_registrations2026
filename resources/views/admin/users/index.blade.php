@extends('layouts.admin')
@section('page-title', 'Students')
@section('content')
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white py-4 border-0">
            <div class="mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-filter-circle me-2 text-orange"></i>Quick Academy Filters</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.users') }}" 
                       class="btn btn-sm {{ !request('academy_id') ? 'btn-dark' : 'btn-outline-secondary' }}" 
                       style="border-radius: 12px; px-3">All Students</a>
                    
                    @php
                        $academyColors = [
                            'Amman' => '#10b981', // Green
                            'Irbid' => '#ef4444', // Red
                            'Zarqa' => '#3b82f6', // Blue
                            'Balqa' => '#ff7900', // Orange
                        ];
                    @endphp

                    @foreach($academies as $academy)
                        @php 
                            $color = $academyColors[$academy->location] ?? '#6c757d'; // Default Gray
                            $isActive = request('academy_id') == $academy->id;
                        @endphp
                        <a href="{{ route('admin.users', array_merge(request()->query(), ['academy_id' => $academy->id])) }}" 
                           class="btn btn-sm text-white"
                           style="border-radius: 12px; background-color: {{ $isActive ? $color : 'transparent' }}; border: 2px solid {{ $color }}; color: {{ $isActive ? '#fff' : $color }} !important; px-3;">
                           {{ $academy->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <form class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Search Students</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Name, email, or phone...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Academy</label>
                    <select name="academy_id" class="form-select form-select-sm">
                        <option value="">All Academies</option>
                        @foreach($academies as $academy)
                            <option value="{{ $academy->id }}" {{ request('academy_id') == $academy->id ? 'selected' : '' }}>{{ $academy->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Account Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Filtration</label>
                    <select name="filtration_status" class="form-select form-select-sm">
                        <option value="">All Filtration</option>
                        <option value="Pending" {{ request('filtration_status') === 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="Accepted for Interview" {{ request('filtration_status') === 'Accepted for Interview' ? 'selected' : '' }}>✓ Accepted</option>
                        <option value="Rejected" {{ request('filtration_status') === 'Rejected' ? 'selected' : '' }}>✗ Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-orange flex-grow-1" type="submit">Apply Filters</button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                            <i class="bi bi-sliders"></i>
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'filtration_status', 'gender', 'academy_id', 'cohort_id', 'date_from', 'date_to']))
                            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i></a>
                        @endif
                    </div>
                </div>

                <div class="col-12 mt-0">
                    <div class="collapse {{ request()->anyFilled(['cohort_id', 'date_from', 'date_to', 'gender']) ? 'show' : '' }}" id="advancedFilters">
                        <div class="p-3 border rounded-3 bg-light mt-2">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Gender</label>
                                    <select name="gender" class="form-select form-select-sm">
                                        <option value="">All Genders</option>
                                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Cohort</label>
                                    <select name="cohort_id" class="form-select form-select-sm">
                                        <option value="">All Cohorts</option>
                                        @foreach($cohorts as $cohort)
                                            <option value="{{ $cohort->id }}" {{ request('cohort_id') == $cohort->id ? 'selected' : '' }}>{{ $cohort->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Joined From</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Joined To</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status & Filtration</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            @php
                                $personalPhoto = $u->documents->filter(fn($d) => $d->documentRequirement && (stripos($d->documentRequirement->name, 'Personal Photo') !== false || stripos($d->documentRequirement->name, 'Personal Image') !== false || stripos($d->documentRequirement->name, 'الشخصيه') !== false))->first();
                            @endphp
                            <tr>
                                <td>
                                    @if($personalPhoto && $personalPhoto->file_path)
                                        <img src="{{ asset('storage/' . $personalPhoto->file_path) }}" 
                                             alt="Photo" 
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #ff7900;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-orange fw-bold"
                                             style="width: 40px; height: 40px; border: 2px solid #ff7900;">
                                            {{ strtoupper(substr($u->profile->first_name_en ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $u->profile->first_name_en ?? '-' }}
                                    {{ $u->profile->last_name_en ?? '' }}
                                </td>
                                <td class="small">{{ $u->email }}</td>
                                <td class="small">
                                    <div class="d-flex align-items-center gap-1">
                                        {{ $u->profile->phone ?? '-' }}
                                        @if($u->profile && $u->profile->phone_verified)
                                            <i class="bi bi-check-circle-fill text-success" title="Verified" style="font-size: 0.75rem;"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                    @if($u->is_active)<span class="badge bg-success" style="width: fit-content;">Active Account</span>@else<span
                                    class="badge bg-secondary" style="width: fit-content;">Inactive Account</span>@endif
                                    
                                    <form method="POST" action="{{ route('admin.users.filtration', $u) }}" class="m-0 p-0">
                                        @csrf
                                        <select name="filtration_status" class="form-select form-select-sm fw-bold text-center 
                                            @if($u->filtration_status == 'Accepted for Interview') text-success border-success bg-success bg-opacity-10
                                            @elseif($u->filtration_status == 'Rejected') text-danger border-danger bg-danger bg-opacity-10
                                            @else text-warning border-warning bg-warning bg-opacity-10
                                            @endif" 
                                            style="min-width: 150px; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 20px;" onchange="this.form.submit()">
                                            <option value="" {{ is_null($u->filtration_status) ? 'selected' : '' }}>⏳ Pending Review</option>
                                            <option value="Accepted for Interview" {{ $u->filtration_status === 'Accepted for Interview' ? 'selected' : '' }}>✓ Accepted for Interview</option>
                                            <option value="Rejected" {{ $u->filtration_status === 'Rejected' ? 'selected' : '' }}>✗ Rejected</option>
                                        </select>
                                    </form>
                                    </div>
                                </td>
                                <td class="small text-muted">{{ $u->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-orange"><i
                                                class="bi bi-eye"></i></a>
                                        <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                                            @csrf
                                            <button
                                                class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                title="{{ $u->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="bi bi-{{ $u->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No students found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white">{{ $users->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection