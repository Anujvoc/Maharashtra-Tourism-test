@php
    // Yahan se steps define ho rahe hain – industrial form ke liye
    $steps = [
        1 => 'Basic Information',
        2 => 'Facilities & Services',
        3 => 'Documents Upload',
        4 => 'Review & Submit',
    ];

    // Total steps
    $totalSteps = count($steps);

    // Ab progress DB me store karne ki zaroorat nahi,
    // sirf current_step se hi 'done' treat kar lo
    $done = $application->current_step - 1 ?? 1;

    $p = [
        'done'  => $done,
        'total' => $totalSteps,
    ];
@endphp

<div class="step-indicator d-flex mb-4">
    <?php
    // dd($steps);
    foreach ($steps as $num => $label):

        $isCompleted = $num <= $p['done'];
        $isActive    = $num == $application->current_step;

        $circleBg    = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand)' : '#e9ecef');
        $circleColor = $isCompleted || $isActive ? 'white' : '#6c757d';
        $labelColor  = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand)' : '#6c757d');
        $labelWeight = $isActive ? '700' : '600';

        $stepUrl = $isCompleted
            ? route('industrial.wizard.show', [$application, 'step' => $num])
            : 'javascript:void(0)';
    ?>
        <a href="<?= $stepUrl ?>"
           style="text-decoration:none; flex:1;"
           class="<?= $isCompleted ? 'step-link' : 'step-disabled' ?>"
           <?= $isCompleted ? '' : 'onclick="return false;"' ?>>
          <div class="step" style="display:flex; flex-direction:column; align-items:center; padding:5px;">
            <div class="step-circle"
                 style="width:48px;height:48px;border-radius:50%;background-color:<?= $circleBg ?>;
                        display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;
                        color:<?= $circleColor ?>;font-weight:700;font-size:1.1rem;border:3px solid #fff;
                        box-shadow:0 4px 12px rgba(0,0,0,0.08);transition:all .3s ease;
                        transform:<?= $isActive ? 'scale(1.05)' : 'scale(1)' ?>;">
              <?= $isCompleted ? '✓' : $num ?>
            </div>
            <div class="step-label"
                 style="font-size:0.9rem;text-align:center;font-weight:<?= $labelWeight ?>;
                        color:<?= $labelColor ?>;transition:all .3s ease;">
              <?= $label ?>
            </div>
          </div>
        </a>
    <?php endforeach; ?>
</div>
