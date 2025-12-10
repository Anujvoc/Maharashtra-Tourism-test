
@extends('admin.layouts.app')

@section('title', 'Category Forms')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Category</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">All</li>
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
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4">
              <i class="bi bi-plus-lg me-2"></i>Add Category
            </a>

          </div>
        </div>

				<div class="card mt-2">

					<div class="card-body">

						<div class="table-responsive mt-2">
							<table id="applicationFormTable" class="table table-striped table-bordered">
								<thead>

                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Name</th>

                                        <th width="10%">Status</th>
                                        <th width="20%" class="no_action">Actions</th>
                                    </tr>
								</thead>


                                <tbody>

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
    $(function () {
        let table = $('#applicationFormTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.categories.data') }}',
            order: [[0, 'desc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
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
