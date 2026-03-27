<!-- @php
    $progress = $registration->progress ?? ['done' => 0, 'total' => 6];
    $done  = $progress['done'];
    $total = $progress['total'];

    $steps = [
        1 => 'General Details',
        2 => 'Project Details',
        3 => 'Investment',
        4 => 'Finance',
        5 => 'Upload Documents',
        6 => 'Declaration',
    ];

    $percentage = $total > 0 ? ($done / $total) * 100 : 0;
@endphp -->

@php
    // Default registration fallback
    $registration = $registration ?? (object)[
        'progress' => ['done' => 0, 'total' => 6],
        'current_step' => 1
    ];

    $progress = $registration->progress ?? ['done' =>0, 'total' => 6];

    $done  = $progress['done'] ?? 1;
    $total = $progress['total'] ?? 6;

    $steps = [
        1 => 'General Details',
        2 => 'Adventure Activity',
        3 => 'Photo and Signature',
        4 => 'Upload Documents',
        5 => 'Declaration',
    ];

    $percentage = $total > 0 ? ($done / $total) * 100 : 0;
@endphp

<div class="progress mb-4" style="height:6px;">
    <div class="progress-bar bg-success"
         style="width: {{ round($percentage) }}%;">
    </div>
</div>

<div class="d-flex step-indicator mb-4">
@foreach($steps as $num => $label)

    @php
        $completed = $num <= $done;
        $active    = $num == $registration->current_step;

        $url = $completed
            ? route('provisional.wizard.show', $num)
            : 'javascript:void(0)';
    @endphp

    <a href="{{ $url }}"
       class="flex-fill text-center {{ $completed ? '' : 'disabled' }}"
       style="text-decoration:none;">

        <div class="mb-2">
            <span class="rounded-circle d-inline-flex
                align-items-center justify-content-center"
                style="
                    width:42px;height:42px;
                    background:{{ $completed ? '#28a745' : ($active ? '#ff6600' : '#ddd') }};
                    color:#fff;font-weight:700;">
                {{ $completed ? '✓' : $num }}
            </span>
        </div>

        <div style="font-size:14px;
            color:{{ $completed ? '#28a745' : ($active ? '#ff6600' : '#999') }};
            font-weight:{{ $active ? '700' : '500' }}">
            {{ $label }}
        </div>
    </a>

@endforeach
</div>
