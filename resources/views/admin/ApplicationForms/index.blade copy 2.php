@extends('admin.layouts.app')

@section('title', 'Application Forms')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Application Forms</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">All Forms</li>
            </ol>
          </nav>
        </div>
      </div>

      <div class="row g-3">

        <div class="col-auto">
          <div class="position-relative">
            <form method="GET">
              <input class="form-control px-5" type="search" name="q" value="{{ $q ?? '' }}" placeholder="Search Country...">
              <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
            </form>
          </div>
        </div>

        <div class="col-auto align-items-end ">
          <div class="d-flex align-items-center gap-2 justify-content-lg-end">
            {{-- Keep Export static for now --}}
            <button class="btn btn-filter px-4">
              <i class="bi bi-box-arrow-right me-2"></i>Export
            </button>

          </div>
        </div>

				<div class="card mt-2">

					<div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0">All Applications</h3>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="example" class="table table-striped table-bordered">

                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Applicant Name</th>
                                                                <th>Email</th>
                                                                <th>Registration ID</th>
                                                                <th>Forms</th>
                                                                <th>Status</th>
                                                                <th>Submitted Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($registration_data as $app)
                                                                @php

                                                                    $forms = App\Models\Admin\ApplicationForm::where('id', $app->application_form_id)->first();
                                                                    $user = App\Models\User::where('id', $app->user_id)->first();
                                                                    $statusColors = [
                                                                        'pending'     => 'rgb(108, 117, 125)',
                                                                        'submitted' => 'rgb(255, 193, 7)',
                                                                        'approved'  => 'rgb(25, 135, 84)',
                                                                        'rejected'  => 'rgb(220, 53, 69)',
                                                                    ];
                                                                    $bgColor = $statusColors[strtolower($app->status)] ?? 'rgb(108, 117, 125)';


                                                                    $routeName = null;
                                                                    $formName = match($app->model) {
                                                                        \App\Models\frontend\ApplicationForm\AdventureApplication::class => 'Adventure Tourism',
                                                                        \App\Models\frontend\ApplicationForm\AgricultureRegistration::class => 'Agro Tourism',
                                                                        \App\Models\Application::class => 'General Application',
                                                                        default => class_basename($app->model),
                                                                    };

                                                                    $modelParam = strtolower(class_basename($app->model));
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $user->name ?? '' }}</td>
                                                                    <td>{{ $user->email ?? '' }}</td>
                                                                    <td class="fw-semibold">{{ $app->registration_id ?? '—' }}</td>
                                                                    <td>{{ $forms->name ?? '' }}</td>

                                                                    <td>

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

                                                                        <a href="{{ route('admin.ApplicationForms.model.show', ['model' => $modelParam, 'id' => $app->id]) }}"
                                                                            class="btn btn-sm"
                                                                            style="background-color:#055f0e; color:#fff;">
                                                                             <i class="bi bi-eye me-1"></i>
                                                                              {{-- View --}}
                                                                         </a>


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
				</div>


    </div>
  </main>

@endsection

@push('scripts')

<script>
    $(function () {
        let table = $('#applicationFormTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.application-forms.data') }}',
            order: [[0, 'desc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'short_description', name: 'short_description' },
                { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ],
            pageLength: 10,
            language: {
                searchPlaceholder: "Search by name or description...",
                search: "",
            },
            dom: '<"d-flex justify-content-between align-items-center mb-2"Bf>rt<"d-flex justify-content-between mt-3"lp>',
            buttons: [
    {
        extend: 'print',
        text: '<i class="bi bi-printer"></i> Print',
        className: 'btn btn-secondary btn-sm text-white'
    },
    {
        extend: 'excel',
        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
        className: 'btn btn-success btn-sm text-white'
    },
    {
        extend: 'csv',
        text: '<i class="bi bi-file-earmark-text"></i> CSV',
        className: 'btn btn-primary btn-sm text-white'
    },
],

        });
    });
</script>
@endpush
