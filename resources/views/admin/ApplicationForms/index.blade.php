@extends('backend.layouts.app')

@section('title', 'Application Forms')

@push('styles')
@endpush

@section('content')

  <main class="main-wrapper">
        <div class=" container-fluid px-4 mt-4">
    <div class="main-content">
      <!--breadcrumb-->
       <div class="bg-image" style="min-height: 100px;">
          <div class="bg-image" style="background-image: url('{{ asset('backend/mah-logo-300x277.png') }}'); background-size: cover; background-position: center; min-height: 100px;"> 

        <div class="bg-gd-white-op-l">

            <div class="d-flex justify-content-between align-items-center content py-3">

                <h3 class="text-black-75 text-center text-sm-start mb-0">

                  Application Forms

                </h3>

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb  px-3 py-2 mb-0">

                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Application Forms</li>

                    </ol>

                </nav>

            </div>

        </div>

    </div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">

    <div class="row align-items-center g-3">
      <div class="col-lg-5 col-md-6">
        <form method="GET">
          <div class="position-relative">
            <input 
              type="search" 
              name="q" 
              value="{{ $q ?? '' }}" 
              class="form-control rounded-pill ps-5"
              placeholder="Search Forms..."
            >
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
              <i class="bi bi-search"></i>
            </span>
          </div>
        </form>
      </div>

    
      <!-- Buttons -->
      <div class="col-lg-4 col-md-6 text-md-end">
          {{-- 
        <div class="d-flex gap-2 justify-content-md-end flex-wrap">

          <button class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-box-arrow-down me-1"></i> Export
          </button>
--}}
          <a href="{{ route('admin.application-forms.create') }}" class="btn btn-primary rounded-pill px-3">
            <i class="fa fa-plus me-1"></i> Add Application Forms
          </a>

        </div>
      </div>

    </div>

  </div>
</div>

        <div class="card mt-2">

          <div class="card-body">

            <div class="table-responsive mt-2">
              <table id="applicationFormTable" class="table table-striped table-bordered">
                <thead>

                  <tr>
                    <th width="5%">#</th>
                    <th width="10%">Image</th>
                    <th width="25%">Name</th>
                    <th width="25%">Short Description</th>
                    <th width="10%">Status</th>
                    <th width="20%" class="no_action">Actions</th>
                  </tr>
                </thead>


                <tbody>
                  {{-- @forelse ($forms as $key => $form)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $form->name?? '' }}</td>
                    <td>{{ $form->image ?? '—' }}</td>
                    <td>
                      @if($form->is_active == 1)
                      <span class="badge bg-success">Active</span>
                      @elseif($form->is_active == 0)
                      <span class="badge bg-danger">Inactive</span>

                      @else
                      <span class="badge bg-secondary">—</span>
                      @endif
                    </td>

                    <td class="d-flex gap-2">
                      <a href="{{ route('admin.application-forms.edit',$form->id) }}"
                        class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <a href="{{ route('admin.application-forms.destroy', $form->id) }}"
                        class="btn btn-danger btn-sm deletebutton rounded-circle"
                        style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">No countries found.</td>
                  </tr>
                  @endforelse --}}
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
     function reloadStudentTable() {
        $('#applicationFormTable').DataTable().ajax.reload();
    }

    $(function () {
      if ($.fn.DataTable.isDataTable('#applicationFormTable')) {
            $('#applicationFormTable').DataTable().destroy();
        }
      let table = $('#applicationFormTable').DataTable({
         lengthMenu: [
                [15, 25, 50,75,100, -1],
                [15, 25, 50,75,100, "All"]
            ],
            displayStart: 0,
            pageLength: 15,
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
        dom: "<'row mb-2 align-items-center'" +

                "<'col-sm-6 d-flex align-items-center'B>" +

                "<'col-sm-6'f>" +

                ">" +

                "<'row'<'col-sm-12'tr>>" +

                "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",

            buttons: [{

                    extend: 'copyHtml5',

                    className: 'btn btn-sm btn-primary me-1 mb-1',

                    text: '<i class="fa fa-copy me-1"></i> Copy',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        modifier: {
                            page: 'all'
                        }
                    }

                },

                {

                    extend: 'csvHtml5',

                    className: 'btn btn-sm btn-primary me-1 mb-1',

                    text: '<i class="fa fa-file-csv me-1"></i> CSV',exportOptions: {
                        columns: ':not(:last-child)',
                        modifier: {
                            page: 'all'
                        }
                    }

                },

                {

                    extend: 'excelHtml5',

                    className: 'btn btn-sm btn-primary me-1 mb-1',

                    text: '<i class="fa fa-file-excel me-1"></i> Excel',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        modifier: {
                            page: 'all'
                        }
                    }

                },

                {

                    extend: 'pdfHtml5',

                    className: 'btn btn-sm btn-primary me-1 mb-1',

                    text: '<i class="fa fa-file-pdf me-1"></i> PDF',exportOptions: {
                        columns: ':not(:last-child)',
                        modifier: {
                            page: 'all'
                        }
                    }

                },

                {

                    extend: 'print',

                    className: 'btn btn-sm btn-primary me-1 mb-1',

                    text: '<i class="fa fa-print me-1"></i> Print',exportOptions: {
                        columns: ':not(:last-child)',
                        modifier: {
                            page: 'all'
                        }
                    }

                }

            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin text-danger"></i> Loading...'
            }

      });
    });
  </script>
@endpush