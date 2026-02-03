@extends('admin.layouts.app')

@section('title', 'Roles')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Roles</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">All Roles</li>
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
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary px-4">
              <i class="bi bi-plus-lg me-2"></i>Add Roles
            </a>

           
          </div>
        </div>

				<div class="card mt-2">

					<div class="card-body">

						<div class="table-responsive mt-2">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
                                        <th>Sl No.</th>
                                        <th>Role Name</th>
                                        <th>Status</th>
                                        <th class="no_action">Actions</th>
									</tr>
								</thead>


                                <tbody>
                                    @forelse ($roles as $key => $role)
                                      <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $role->name?? '' }}</td>
                                        <td>
                                            @if($role->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @elseif($role->status == 0)
                                                <span class="badge bg-danger">Inactive</span>

                                            @else
                                                <span class="badge bg-secondary">—</span>
                                            @endif
                                        </td>



                                        <td class="d-flex gap-2">
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-sm rounded-circle">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <a href="{{ route('admin.roles.destroy', $role->id) }}" class="btn btn-danger btn-sm deletebutton rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                      </tr>
                                    @empty
                                      <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No countries found.</td>
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

</script>
@endpush
