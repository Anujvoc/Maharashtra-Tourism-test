@extends('admin.layouts.app')

@section('title', 'Application Forms')

@section('content')

<main class="main-wrapper">
    <div class="main-content">

      <!-- Breadcrumb -->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Application Forms</div>
        <div class="ps-3">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active">
                {{ auth()->user()->hasRole('Super Admin')
                    ? 'All Applications'
                    : 'Pending at '.auth()->user()->getRoleNames()->first() }}
            </li>
          </ol>
        </div>
      </div>

      <div class="card mt-2">
        <div class="card-body">

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
                    $user  = \App\Models\User::find($app->user_id);
                    $form  = \App\Models\Admin\ApplicationForm::find($app->application_form_id);

                    $statusColors = [
                        'Pending' => 'rgb(255, 193, 7)',
                        'Returned' => 'rgb(220, 53, 69)',
                        'Clarification' => 'rgb(13, 110, 253)',
                        'Approved' => 'rgb(25, 135, 84)',
                    ];

                    $bgColor = $statusColors[$app->status] ?? 'rgb(108, 117, 125)';
                    $modelParam = strtolower(class_basename($app->model));
                @endphp

                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $user->name ?? '' }}</td>
                  <td>{{ $user->email ?? '' }}</td>
                  <td class="fw-semibold">{{ $app->registration_id ?? '—' }}</td>
                  <td>{{ $form->name ?? class_basename($app->model) }}</td>

                  <td>
                    <button class="btn btn-sm rounded-pill px-3 py-1 fw-bold"
                        style="background-color: {{ $bgColor }}; color:#fff;">
                        {{ $app->status }}
                    </button>

                    @if(auth()->user()->hasRole('Super Admin'))
                        <div class="small text-muted">
                            Stage: {{ $app->original->current_stage }}
                        </div>
                    @endif
                  </td>

                  <td>
                    {{ $app->submitted_at
                        ? $app->submitted_at->format('d M Y, h:i A')
                        : '—' }}
                  </td>

                  <td>
                    <a href="{{ route('admin.ApplicationForms.model.show', [
                        'model' => $modelParam,
                        'id' => $app->id
                    ]) }}"
                    class="btn btn-sm"
                    style="background-color:#055f0e; color:#fff;">
                        <i class="bi bi-eye"></i>
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
</main>

@endsection

@push('scripts')
<script>
$(document).ready(function () {


    if ( $.fn.DataTable.isDataTable('#example65') ) {
        $('#example56').DataTable().clear().destroy();
    }

    $('#example45').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        dom: '<"d-flex justify-content-between align-items-center mb-2"Bf>rt<"d-flex justify-content-between mt-3"lp>',
        buttons: [
            {
                extend: 'print',
                className: 'btn btn-secondary btn-sm text-white'
            },
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm text-white'
            },
            {
                extend: 'csv',
                className: 'btn btn-primary btn-sm text-white'
            }
        ],
        language: {
            searchPlaceholder: "Search...",
            search: ""
        }
    });

});
</script>
@endpush

