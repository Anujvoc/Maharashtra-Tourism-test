{{-- resources/views/frontend/Application/provisional/_stepper.blade.php --}}

@php
    // Wizard progress from ProvisionalRegistration
    $progress = $registration->progress ?? ['done' => 0, 'total' => 6];
    $done     = $progress['done']  ?? 0;
    $total    = $progress['total'] ?? 6;

    $steps = [
        1 => 'General Details',
        2 => 'Project Details',
        3 => 'Investment',
        4 => 'Finance',
        5 => 'Upload Documents',
        6 => 'Declaration',
    ];
@endphp

{{-- Optional progress bar --}}
@php
    $percentage = $total > 0 ? ($done / $total) * 100 : 0;
@endphp

<div class="progress mb-4" style="height: 6px;">
    <div class="progress-bar bg-success"
        role="progressbar"
        style="width: {{ number_format($percentage, 0) }}%;"
        aria-valuenow="{{ number_format($percentage, 0) }}"
        aria-valuemin="0" aria-valuemax="100">
    </div>
</div>

<div class="step-indicator d-flex mb-4">
    @foreach($steps as $num => $label)
        @php
            $isCompleted = $num <= $done;
            $isActive    = $num == $application->current_step;

            $circleBg    = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand, #ff6600)' : '#e9ecef');
            $circleColor = $isCompleted || $isActive ? 'white' : '#6c757d';
            $labelColor  = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand, #ff6600)' : '#6c757d');
            $labelWeight = $isActive ? '700' : '600';

            // Completed steps only clickable
            $stepUrl = $isCompleted
                ? route('provisional.wizard.show', [$application, 'step' => $num])
                : 'javascript:void(0)';
        @endphp

        <a href="{{ $stepUrl }}"
           style="text-decoration:none; flex:1;"
           class="{{ $isCompleted ? 'step-link' : 'step-disabled' }}"
           {!! $isCompleted ? '' : 'onclick="return false;"' !!}>

            <div class="step" style="display:flex; flex-direction:column; align-items:center; padding:5px;">

                <div class="step-circle"
                    style="
                        width:48px; height:48px; border-radius:50%;
                        background-color:{{ $circleBg }};
                        display:flex; align-items:center; justify-content:center;
                        margin-bottom:0.75rem;
                        color:{{ $circleColor }};
                        font-weight:700; font-size:1.1rem;
                        border:3px solid white;
                        box-shadow:0 4px 12px rgba(0,0,0,0.08);
                        transition:all 0.3s ease;
                        transform:{{ $isActive ? 'scale(1.05)' : 'scale(1)' }};
                    ">
                    {{ $isCompleted ? '✓' : $num }}
                </div>

                <div class="step-label"
                    style="
                        font-size:0.9rem;
                        text-align:center;
                        font-weight:{{ $labelWeight }};
                        color:{{ $labelColor }};
                        transition:all 0.3s ease;
                    ">
                    {{ $label }}
                </div>
            </div>

        </a>
    @endforeach
</div>
