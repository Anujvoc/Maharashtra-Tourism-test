@extends('frontend.layouts2.master')
@section('title', 'My Applications')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>My Applications</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">All Applications</h3>

                            <a href="{{ route('frontend.application-forms.index') }}"
                                class="ms-auto btn btn-primary button"><i class="bi bi-plus-circle me-1"></i>New
                                Application</a>
                        </div>

                        <div class="card-body">
                            {{-- Alerts --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('info'))
                                <div class="alert alert-info">{{ session('info') }}</div>
                            @endif

                            {{-- No apps yet --}}
                            @if ($apps->isEmpty())
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center py-5">
                                        <div class="display-6 mb-2">👋</div>
                                        <h5 class="mb-1">No applications yet</h5>
                                        <p class="text-muted mb-3">Click the button below to start your Registration Forms.
                                        </p>

                                        <a href="{{ route('frontend.application-forms.index') }}"
                                            class="btn btn-primary button"><i class="bi bi-rocket-takeoff me-1"></i> Start
                                            New Application</a>
                                    </div>
                                </div>
                            @else
                                {{-- DataTable --}}
                                <div class="table-responsive">
                                    <table id="appsTable"
                                        class="table table-hover table-borderless table-modern table-orange w-100">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Forms</th>
                                                <th>Registration ID</th>
                                                <th>Progress</th>
                                                <th>Current Step</th>
                                                <th>Status</th>
                                                <th>Submitted At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($apps as $app)
                                                @php
                                                    $progress = $app->progress;
                                                    $pct = intval(($progress['done'] / $progress['total']) * 100);
                                                    $badge =
                                                        [
                                                            'draft' => 'secondary',
                                                            'submitted' => 'warning',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                        ][$app->status] ?? 'secondary';

                                                    $stepNames = [
                                                        1 => 'Applicant',
                                                        2 => 'Property',
                                                        3 => 'Accommodation',
                                                        4 => 'Facilities',
                                                        5 => 'Photo & Signature',
                                                        6 => 'Enclosures',
                                                        7 => 'Review',
                                                    ];
                                                    $stepLabel = $stepNames[$app->current_step] ?? $app->current_step;
                                                    $application_forms = DB::table('application_forms')
                                                        ->where('id', $app->application_form_id)
                                                        ->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $application_forms->name ?? '' }}</td>
                                                    <td class="fw-semibold">
                                                        {{ $app->registration_id ?? '—' }}

                                                    </td>

                                                    <td style="min-width:150px">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="progress flex-grow-1" style="height:8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ $pct }}%"></div>
                                                            </div>
                                                            <small
                                                                class="text-muted">{{ $progress['done'] }}/{{ $progress['total'] }}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($app->is_apply)
                                                            <span
                                                                class="badge text-bg-info fs-1 ">{{ ucfirst($app->status) }}</span>
                                                        @else
                                                            <span class="text-muted">{{ $stepLabel }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($app->is_apply)
                                                            <button type="button"
                                                                style="
                                                                    background-color:#055f0e;
                                                                    color:#fff;
                                                                    font-weight:700;
                                                                    border:none;
                                                                    border-radius:8px;
                                                                    padding:.2rem 0.5rem;
                                                                    cursor:pointer;
                                                                "
                                                                class="btn btn-sm  rounded-pill px-3 py-1 fw-bold"
                                                                style="font-size:13px;">
                                                                Applied
                                                            </button>
                                                        @else
                                                            <span
                                                                class="badge text-bg-{{ $badge }}">{{ ucfirst($app->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $app->submitted_at ? $app->submitted_at->format('d M Y, h:i A') : '—' }}
                                                    </td>
                                                    <td>
                                                        @if ($app->status === 'draft')
                                                            <a class="btn btn-sm "
                                                                style="
                                                                    background-color:#fc089f;
                                                                    color:#fff;
                                                                    font-weight:700;
                                                                    border:none;
                                                                    border-radius:8px;
                                                                    padding:.2rem 0.5rem;
                                                                    cursor:pointer;
                                                                "
                                                                href="{{ route('wizard.show', [$app, 'step' => $app->current_step]) }}">
                                                                <i class="bi bi-play-circle me-1"></i> Resume
                                                            </a>
                                                            @if ($progress['done'] > 0)
                                                                <a class="btn btn-sm "
                                                                    style="
                                                                    background-color:#0d01ff;
                                                                    color:#fff;
                                                                    font-weight:700;
                                                                    border:none;
                                                                    border-radius:8px;
                                                                    padding:.2rem 0.5rem;
                                                                    cursor:pointer;
                                                                "
                                                                    href="{{ route('wizard.show', [$app, 'step' => 1]) }}">
                                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('applications.report', $app->id) }}"
                                                                class="btn btn-sm" target="_blank"
                                                                style="background-color:#055f0e; color:#fff;">
                                                                <i class="bi bi-eye me-1"></i> View/Print
                                                            </a>


                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>


                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="apps1Table"
                                            class="table table-hover table-borderless table-modern table-orange w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Forms</th>
                                                    <th>Registration ID</th>
                                                    <th>Status</th>
                                                    <th>Submitted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registration_data as $app)
                                                    @php
                                                        // Map statuses to RGB colors
                                                        $statusColors = [
                                                            'pending'     => 'rgb(108, 117, 125)',   // gray
                                                            'submitted' => 'rgb(255, 193, 7)',     // yellow
                                                            'approved'  => 'rgb(25, 135, 84)',     // green
                                                            'rejected'  => 'rgb(220, 53, 69)',     // red
                                                        ];
                                                        $bgColor = $statusColors[strtolower($app->status)] ?? 'rgb(108, 117, 125)';

                                                        // Get form name (fallback to model name)
                                                        $form = $forms[$app->application_form_id] ?? null;
                                                        $formName = $form->name ?? class_basename($app->model);

                                                        // Determine route name per model (adjust mapping to your app)
                                                        $routeName = null;
                                                        if ($app->model === \App\Models\frontend\ApplicationForm\AdventureApplication::class) {
                                                            $routeName = 'frontend.applications.adventure.report';
                                                        } elseif ($app->model === \App\Models\frontend\ApplicationForm\TourismApartment::class) {
                                                            $routeName = 'frontend.applications.tourism.report';

                                                        } elseif ($app->model === \App\Models\frontend\ApplicationForm\AgricultureRegistration::class) {
                                                            $routeName = 'applications.Agriculture.tourism.report';
                                                        }
                                                         elseif ($app->model === \App\Models\frontend\ApplicationForm\ProvisionalRegistration::class) {
                                                            $routeName = 'applications.provisional.show';
                                                        }
                                                        // Add other model => route mappings here...
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $formName }}</td>
                                                        <td class="fw-semibold">{{ $app->registration_id ?? '—' }}</td>

                                                        <td>
                                                            {{-- single dynamic button using RGB background --}}
                                                            <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold"
                                                                style="
                                                                    background-color: {{ $bgColor }};
                                                                    color: #fff;
                                                                    font-weight: 700;
                                                                    border: none;
                                                                    border-radius: 8px;
                                                                ">
                                                                {{ ucfirst($app->status) }}
                                                            </button>
                                                        </td>

                                                        <td>
                                                            {{ $app->submitted_at ? $app->submitted_at->format('d M Y, h:i A') : '—' }}
                                                        </td>
                                                        <td>
                                                            {{-- STAMP DUTY APPLICATION --}}
                                                            @if ($app->model === \App\Models\frontend\ApplicationForm\StampDutyApplication::class)

                                                                @php
                                                                    $applicationId = $app->id;
                                                                    $application = \App\Models\frontend\ApplicationForm\StampDutyApplication::find($applicationId);
                                                               
                                                                @endphp

                                                                @if ($applicationId)
                                                                    @if ($app->status === 'draft')
                                                                        {{-- Resume from current_step --}}
                                                                        <a class="btn btn-sm"
                                                                           style="
                                                                               background-color:#fc089f;
                                                                               color:#fff;
                                                                               font-weight:700;
                                                                               border:none;
                                                                               border-radius:8px;
                                                                               padding:.2rem 0.6rem;
                                                                               cursor:pointer;
                                                                           "
                                                                           href="{{ route('stamp-duty.wizard', [
                                                                               'id'          => $app->application_form_id,
                                                                               'step'        => $application->current_step,
                                                                               'application' => $app->id ?? null,
                                                                           ]) }}">
                                                                            <i class="bi bi-play-circle me-1"></i> Resume
                                                                        </a>

                                                                        {{-- Edit from Step 1 --}}
                                                                        <a class="btn btn-sm"
                                                                           style="
                                                                               background-color:#0d01ff;
                                                                               color:#fff;
                                                                               font-weight:700;
                                                                               border:none;
                                                                               border-radius:8px;
                                                                               padding:.2rem 0.6rem;
                                                                               cursor:pointer;
                                                                           "
                                                                           href="{{ route('stamp-duty.wizard', [
                                                                               'id'          => $app->application_form_id,
                                                                               'step'        => 1,
                                                                               'application' => $app->id ?? null,
                                                                           ]) }}">
                                                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                                                        </a>
                                                                    @else
                                                                        {{-- Submitted/Approved/Rejected => View/Print --}}
                                                                        <a href="{{ route('stamp-duty.reports', [
                                                                                'id' => $app->id,
                                                                            ]) }}"
                                                                           class="btn btn-sm"
                                                                           target="_blank"
                                                                           style="background-color:#055f0e; color:#fff;">
                                                                            <i class="bi bi-eye me-1"></i> View/Print
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted small">No Active</span>
                                                                @endif


                                                            {{-- PROVISIONAL REGISTRATION --}}
                                                            @elseif ($app->model === \App\Models\frontend\ApplicationForm\ProvisionalRegistration::class)

                                                                @php
                                                                    // Safe resolve application id:
                                                                    $applicationId = $app->application_id;

                                                                    // agar phir bhi null hai (edge case), try lookup via registration_id
                                                                    if (!$applicationId && !empty($app->registration_id)) {
                                                                        $applicationId = \App\Models\Application::where('registration_id', $app->registration_id)->value('id');
                                                                    }
                                                                @endphp

                                                                @if ($applicationId)
                                                                    @if ($app->status === 'draft')
                                                                        {{-- Resume from current_step --}}
                                                                        <a class="btn btn-sm"
                                                                           style="
                                                                               background-color:#fc089f;
                                                                               color:#fff;
                                                                               font-weight:700;
                                                                               border:none;
                                                                               border-radius:8px;
                                                                               padding:.2rem 0.6rem;
                                                                               cursor:pointer;
                                                                           "
                                                                           href="{{ route('provisional.wizard.show', [
                                                                               'application' => $applicationId,
                                                                               'step'        => $app->current_step ?? 1,
                                                                           ]) }}">
                                                                            <i class="bi bi-play-circle me-1"></i> Resume
                                                                        </a>

                                                                        {{-- Edit from Step 1 --}}
                                                                        <a class="btn btn-sm"
                                                                           style="
                                                                               background-color:#0d01ff;
                                                                               color:#fff;
                                                                               font-weight:700;
                                                                               border:none;
                                                                               border-radius:8px;
                                                                               padding:.2rem 0.6rem;
                                                                               cursor:pointer;
                                                                           "
                                                                           href="{{ route('provisional.wizard.show', [
                                                                               'application' => $applicationId,
                                                                               'step'        => 1,
                                                                           ]) }}">
                                                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                                                        </a>
                                                                    @else
                                                                        {{-- Submitted/Approved/Rejected => View/Print --}}
                                                                        <a href="{{ route('applications.provisional.show', [
                                                                                'application' => $app->id,
                                                                            ]) }}"
                                                                           class="btn btn-sm"
                                                                           target="_blank"
                                                                           style="background-color:#055f0e; color:#fff;">
                                                                            <i class="bi bi-eye me-1"></i> View/Print
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted small">No linked application</span>
                                                                @endif


                                                            {{-- OTHER MODELS --}}
                                                            @else
                                                                @php
                                                                    $routeName = null;

                                                                    if ($app->model === \App\Models\frontend\ApplicationForm\AdventureApplication::class) {
                                                                        $routeName = 'frontend.applications.adventure.report';
                                                                    } elseif ($app->model === \App\Models\frontend\ApplicationForm\TourismApartment::class) {
                                                                        $routeName = 'frontend.applications.tourism.report';
                                                                    } elseif ($app->model === \App\Models\frontend\ApplicationForm\AgricultureRegistration::class) {
                                                                        $routeName = 'applications.Agriculture.tourism.report';
                                                                    } elseif ($app->model === \App\Models\frontend\ApplicationForm\EligibilityRegistration::class) {
                                                                        $routeName = 'eligibility-registrations.show';
                                                                    } elseif ($app->model === \App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration::class) {
                                                                        // $routeName = 'applications.women.tourism.report';
                                                                    } elseif ($app->model === \App\Models\frontend\ApplicationForm\CaravanRegistration::class) {
                                                                        // $routeName = 'applications.caravan.report';
                                                                    }
                                                                @endphp

                                                                @if ($routeName)
                                                                    <a href="{{ route($routeName, $app->id) }}"
                                                                       class="btn btn-sm rounded-pill"
                                                                       target="_blank"
                                                                       style="background-color:#055f0e; color:#fff;">
                                                                        <i class="bi bi-eye me-1"></i> View/Print
                                                                    </a>
                                                                @else
                                                                    <a href="#" class="btn btn-sm btn-secondary disabled">No Action</a>
                                                                @endif
                                                            @endif
                                                        </td>





                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection


@push('styles')
    {{-- ✅ Include DataTables CSS href="{{ route('wizard.show', [$app, 'step' => 7]) }} --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root {
            --brand-500: #ff6600;
            --brand-600: #e65c00;
            --brand-100: #ffe0cc;
        }

        /* Buttons */
        .btn-primary {
            background: var(--brand-500);
            border-color: var(--brand-500);
        }

        .btn-primary:hover {
            background: var(--brand-600);
            border-color: var(--brand-600);
        }

        .progress-bar {
            background: var(--brand-500);
        }

        /* ✅ FORCE header white text */
        table.table-modern thead {
            background-color: #ff6600 !important;
        }

        table.table-modern thead th {
            color: #fff !important;
            font-weight: 700 !important;
            border: none !important;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            text-align: left;
        }

        .table-modern tbody tr:hover {
            background: #fff4e6;
        }

        .table-modern td,
        .table-modern th {
            vertical-align: middle;
        }

        /* DataTable custom UI */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #ff6600 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e65c00 !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--brand-100) !important;
            border-radius: .5rem !important;
            padding: .4rem .6rem !important;
            outline: none !important;
        }

        .dataTables_wrapper .dataTables_filter label {
            font-weight: 600;
            color: #444;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: .5rem;
            border: 1px solid #ddd;
            padding: .2rem .4rem;
        }
    </style>
@endpush


@push('scripts')
    {{-- ✅ Include jQuery & DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#appsTable','apps1Table').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search applications...",
                    lengthMenu: "_MENU_ per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ applications",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },
                columnDefs: [{
                        orderable: false,
                        targets: [6]
                    } // Disable sorting on Action
                ]
            });
        });
    </script>
@endpush
