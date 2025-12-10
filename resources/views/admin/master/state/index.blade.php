@extends('admin.layouts.app')

@section('title', 'State')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">State</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
          </nav>
        </div>
      </div>

      {{-- Search + Buttons --}}
      <div class="row g-3">
        {{-- <div class="col-auto">
            <div class="d-flex flex-wrap gap-2 mt-2">
                <button class="btn btn-sm btn-primary" id="export-print"><i class="bx bx-printer me-1"></i> Print</button>
                <button class="btn btn-sm btn-secondary" id="export-csv"><i class="bx bx-file me-1"></i> CSV</button>
                <button class="btn btn-sm btn-success" id="export-excel"><i class="bx bx-table me-1"></i> Excel</button>
                <button class="btn btn-sm btn-danger" id="export-pdf"><i class="bx bx-file me-1"></i> PDF</button>
                <button class="btn btn-sm btn-warning" id="export-copy"><i class="bx bx-copy me-1"></i> Copy</button>
              </div>
        </div> --}}
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
            <a href="{{ route('admin.master.states.create') }}" class="btn btn-primary px-4">
              <i class="bi bi-plus-lg me-2"></i>Add State
            </a>
          </div>
        </div>






				<div class="card mt-2">

					<div class="card-body">

						<div class="table-responsive mt-2">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
                                        <th>State Name</th>
                                        <th>State Code</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th class="no_action">Actions</th>
									</tr>
								</thead>
                                <tbody>
                                    @forelse($states as $state)
                                      <tr>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ $state->code ?? '—' }}</td>
                                        <td>{{ $state->country->name ?? '-' }}</td>
                                        <td>
                                          <span class="badge bg-{{ $state->is_active ? 'success' : 'secondary' }}">
                                            {{ $state->is_active ? 'Active' : 'Inactive' }}
                                          </span>
                                        </td>

                                        <td class="d-flex gap-2">
                                          <a href="{{ route('admin.master.states.edit', $state->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                          <form method="POST" action="{{ route('admin.master.states.destroy', $state->id) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                          </form>
                                        </td>
                                      </tr>
                                    @empty
                                      <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No state found.</td>
                                      </tr>
                                    @endforelse
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
// Optional custom JS logic can go here
</script>
@endpush
