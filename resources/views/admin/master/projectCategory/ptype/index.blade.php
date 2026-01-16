@extends('admin.layouts.app')

@section('title', 'Project Type')

@push('styles')
@endpush

@section('content')
<main class="main-wrapper">
    <div class="main-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Project Type</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bx bx-home-alt"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Top bar --}}
        <div class="row g-3 align-items-center mb-2">

            {{-- Search --}}
            <div class="col-auto">
                <div class="position-relative">
                    <form method="GET">
                        <input class="form-control px-5"
                               type="search"
                               name="q"
                               value="{{ $q ?? '' }}"
                               placeholder="Search...">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">
                            search
                        </span>
                    </form>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="col-auto ms-auto">
                <div class="d-flex align-items-center gap-2">

                    @can('view ProvisionalProjectType')
                        <button class="btn btn-filter px-4">
                            <i class="bi bi-box-arrow-right me-2"></i>Export
                        </button>
                    @endcan

                    @can('create ProvisionalProjectType')
                        <a href="{{ route('admin.master.projectType.create') }}"
                           class="btn btn-primary px-4">
                            <i class="bi bi-plus-lg me-2"></i>Add Project Type
                        </a>
                    @endcan

                </div>
            </div>
        </div>

        {{-- Table --}}
        @can('view ProvisionalProjectType')
        <div class="card mt-2">
            <div class="card-body">

                <div class="table-responsive mt-2">
                    <table id="applicationFormTable"
                           class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Name</th>
                                <th width="15%">Status</th>
                                <th width="20%" class="no_action">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
        @endcan

    </div>
</main>
@endsection

@push('scripts')
<script>
$(function () {

    @can('view ProvisionalProjectType')
    $('#applicationFormTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.master.projectType.data') }}',
        order: [[0, 'desc']],
        pageLength: 10,

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],

        language: {
            searchPlaceholder: "Search by name...",
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
            }
        ]
    });
    @else
        console.warn('Permission denied: view ProvisionalProjectType');
    @endcan

});
</script>
@endpush
