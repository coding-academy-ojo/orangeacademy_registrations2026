{{-- Step Progress Bar --}}
@php $steps = ['Profile Info', 'Documents', 'Orange Coursat', 'Academy', 'Assessments', 'Questionnaire', 'Review']; @endphp
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            @foreach($steps as $i => $label)
                <div class="text-center flex-fill">
                    <div class="mx-auto mb-2"
                        style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;
                                {{ $step > $i + 1 ? 'background:var(--orange-success);color:white;' : ($step == $i + 1 ? 'background:var(--orange-primary);color:white;' : 'background:var(--orange-grey-200);color:var(--orange-grey-500);') }}">
                        @if($step > $i + 1)
                            <i class="bi bi-check-lg"></i>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="small {{ $step == $i + 1 ? 'fw-bold text-orange' : 'text-muted' }}"
                        style="font-size:0.75rem;">{{ $label }}</div>
                </div>
            @endforeach
        </div>
        <div class="progress" style="height:4px;">
            <div class="progress-bar" style="width:{{ (($step - 1) / 6) * 100 }}%;background:var(--orange-primary);">
            </div>
        </div>
    </div>
</div>