@php($p = $application->progress) {{-- ['done'=>n, 'total'=>6] --}}

<div class="form-container">
    <div class="form-header">
        <h2>Application Form for the  {{ $application_form->name ?? '' }}</h2>
        <p>Please fill out all required fields marked with an asterisk (*)</p>
    </div>

    <div class="step-indicator d-flex">
        <?php
        $steps = [
            1 => 'Applicant Details',
            2 => 'Property Details',
            3 => 'Accommodation',
            4 => 'Facilities',
            5 => 'Photo & Signature',
            6 => 'Documents',
            7 => 'Review & Submit'
        ];

        foreach ($steps as $num => $label):

            $isCompleted = $num <= $p['done'];
            $isActive = $num == $application->current_step;

            // Colors
            $circleBg = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand)' : '#e9ecef');
            $circleColor = $isCompleted || $isActive ? 'white' : '#6c757d';
            $labelColor = $isCompleted ? '#28a745' : ($isActive ? 'var(--brand)' : '#6c757d');
            $labelWeight = $isActive ? '700' : '600';

            // URL for completed steps only
            $stepUrl = $isCompleted
                ? route('wizard.show', [$application, 'step' => $num])
                : 'javascript:void(0)';
        ?>

            <a href="<?= $stepUrl ?>"
               style="text-decoration:none; flex:1;"
               class="<?= $isCompleted ? 'step-link' : 'step-disabled' ?>"
               <?= $isCompleted ? '' : 'onclick="return false;"' ?>>

                <div class="step" style="display:flex; flex-direction:column; align-items:center; padding:5px;">

                    <div class="step-circle"
                        style="
                            width:48px; height:48px; border-radius:50%;
                            background-color:<?= $circleBg ?>;
                            display:flex; align-items:center; justify-content:center;
                            margin-bottom:0.75rem;
                            color:<?= $circleColor ?>;
                            font-weight:700; font-size:1.1rem;
                            border:3px solid white;
                            box-shadow:0 4px 12px rgba(0,0,0,0.08);
                            transition:all 0.3s ease;
                            transform:<?= $isActive ? 'scale(1.05)' : 'scale(1)' ?>;
                        ">
                        <?= $isCompleted ? '✓' : $num ?>
                    </div>

                    <div class="step-label"
                        style="
                            font-size:0.9rem;
                            text-align:center;
                            font-weight:<?= $labelWeight ?>;
                            color:<?= $labelColor ?>;
                            transition:all 0.3s ease;
                        ">
                        <?= $label ?>
                    </div>
                </div>

            </a>

        <?php endforeach; ?>
    </div>




