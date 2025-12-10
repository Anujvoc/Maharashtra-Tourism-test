{{-- @php
    $progress = $progress ?? ['done' => 0, 'total' => 6];
    $done     = $progress['done'] ?? 0;
    $total    = $progress['total'] ?? 6;

    $steps = [
        1 => 'General Details',
        2 => 'Addresses',
        3 => 'Land & Built-up Area',
        4 => 'Project Cost & Purpose',
        5 => 'Upload Documents',
        6 => 'Declaration & Affidavit',
    ];

    $percentage = $total > 0 ? ($done / $total) * 100 : 0;
@endphp

<style>
    :root {
        --brand: #ff6600;
        --brand-dark: #e25500;
    }
    .step-indicator {
        display:flex;
        margin-bottom:1.5rem;
    }
    .step-indicator a {
        flex:1;
        text-decoration:none;
    }
    .step-indicator .step {
        display:flex;
        flex-direction:column;
        align-items:center;
        padding:5px;
    }
</style>

<div class="progress mb-4" style="height:6px;">
    <div class="progress-bar"
         role="progressbar"
         style="width: {{ number_format($percentage, 0) }}%; background-color:var(--brand);"
         aria-valuenow="{{ number_format($percentage, 0) }}"
         aria-valuemin="0"
         aria-valuemax="100">
    </div>
</div>

<div class="step-indicator mb-4">
    @foreach($steps as $num => $label)
        @php
            $isCompleted = $num <= $done;
            $isActive    = $num == $step;

            $circleBg    = $isCompleted ? '#ff9a3d' : ($isActive ? 'var(--brand)' : '#e9ecef');
            $circleColor = $isCompleted || $isActive ? '#ffffff' : '#6c757d';
            $labelColor  = $isActive ? 'var(--brand)' : ($isCompleted ? '#ff6600' : '#6c757d');
            $labelWeight = $isActive ? '700' : '600';

            $applicationId = $application->id ?? null;
            $stepUrl = $isCompleted && $applicationId
    ? route('stamp-duty.wizard', [
        'id'          => $application->application_form_id,
        'step'        => $num,
        'application' => $applicationId
    ])
    : 'javascript:void(0)';

        @endphp

        <a href="{{ $stepUrl }}"
           {!! $isCompleted && $applicationId ? '' : 'onclick="return false;"' !!}>
            <div class="step">
                <div class="step-circle"
                     style="width:48px;height:48px;border-radius:50%;
                            background-color:{{ $circleBg }};
                            display:flex;align-items:center;justify-content:center;
                            margin-bottom:0.75rem;
                            color:{{ $circleColor }};
                            font-weight:700;font-size:1.1rem;
                            border:3px solid #fff;
                            box-shadow:0 4px 12px rgba(0,0,0,0.08);
                            transform:{{ $isActive ? 'scale(1.05)' : 'scale(1)' }};
                            transition:all .3s ease;">
                    {{ $isCompleted ? '✓' : $num }}
                </div>
                <div class="step-label"
                     style="font-size:0.9rem;text-align:center;
                            font-weight:{{ $labelWeight }};
                            color:{{ $labelColor }};">
                    {{ $label }}
                </div>
            </div>
        </a>
    @endforeach
</div> --}}

{{-- resources/views/frontend/Application/stamp_duty/_stepper.blade.php --}}

@php
    // Safe defaults
    $progress = $progress ?? ['done' => 0, 'total' => 6];
    $done     = $progress['done']  ?? 0;
    $total    = $progress['total'] ?? 6;

    $steps = [
        1 => 'General Details',
        2 => 'Addresses',
        3 => 'Land & Built-up Area',
        4 => 'Project Cost & Purpose',
        5 => 'Upload Documents',
        6 => 'Declaration & Affidavit',
    ];

    $percentage = $total > 0 ? ($done / $total) * 100 : 0;

    // For URLs
    $applicationId   = $application->id             ?? null;
    $applicationForm = $application->application_form_id ?? null;
@endphp

<style>
    :root {
        --brand: #ff6600;
        --brand-dark: #e25500;
    }
    .step-indicator {
        display:flex;
        margin-bottom:1.5rem;
    }
    .step-indicator a {
        flex:1;
        text-decoration:none;
    }
    .step-indicator .step {
        display:flex;
        flex-direction:column;
        align-items:center;
        padding:5px;
    }
    .step-circle {
        transition: all .25s ease-in-out;
    }
    .step-indicator a:hover .step-circle {
        transform: scale(1.08);
    }
</style>

{{-- Progress Bar --}}
<div class="progress mb-4" style="height:6px;">
    <div class="progress-bar"
         role="progressbar"
         style="width: {{ number_format($percentage, 0) }}%; background-color:var(--brand);"
         aria-valuenow="{{ number_format($percentage, 0) }}"
         aria-valuemin="0"
         aria-valuemax="100">
    </div>
</div>

{{-- Step Circles --}}
<div class="step-indicator mb-4">
    @foreach($steps as $num => $label)
        @php
            $isCompleted = $num <= $done;
            $isActive    = $num == ($step ?? 1);

            // ✅ Color logic
            // Completed = green, Active = orange, Pending = grey
            $circleBg    = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand)' : '#e9ecef');
            $circleColor = $isCompleted || $isActive ? '#ffffff' : '#6c757d';
            $labelColor  = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand)' : '#6c757d');
            $labelWeight = $isActive ? '700' : '600';

            // URL only if step completed + application exists + application_form_id set
            $stepUrl = ($isCompleted && $applicationId && $applicationForm)
                ? route('stamp-duty.wizard', [
                    'id'          => $applicationForm,
                    'step'        => $num,
                    'application' => $applicationId,
                ])
                : 'javascript:void(0)';
        @endphp

        <a href="{{ $stepUrl }}"
           {!! ($isCompleted && $applicationId && $applicationForm) ? '' : 'onclick="return false;"' !!}>
            <div class="step">
                <div class="step-circle"
                     style="width:48px;height:48px;border-radius:50%;
                            background-color:{{ $circleBg }};
                            display:flex;align-items:center;justify-content:center;
                            margin-bottom:0.75rem;
                            color:{{ $circleColor }};
                            font-weight:700;font-size:1.1rem;
                            border:3px solid #fff;
                            box-shadow:0 4px 12px rgba(0,0,0,0.08);
                            transform:{{ $isActive ? 'scale(1.05)' : 'scale(1)' }};">
                    {{ $isCompleted ? '✓' : $num }}
                </div>
                <div class="step-label"
                     style="font-size:0.9rem;text-align:center;
                            font-weight:{{ $labelWeight }};
                            color:{{ $labelColor }};">
                    {{ $label }}
                </div>
            </div>
        </a>
    @endforeach
</div>

