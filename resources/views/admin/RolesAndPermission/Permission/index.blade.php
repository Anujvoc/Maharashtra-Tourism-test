@extends('admin.layouts.app')

@section('title', 'Permission')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Permission</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">All Permission</li>
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
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary px-4">
              <i class="bi bi-plus-lg me-2"></i>Add Permission
            </a>
            <a href="{{ route('admin.add.roles.permission') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-2"></i> Assign Role Permission
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
                                        <th>Permission Name</th>
                                        <th>Group Name</th>
                                        <th class="no_action">Actions</th>
									</tr>
								</thead>


                                <tbody>
                                    @forelse ($permissions as $key => $permission)
                                      <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $permission->name?? '' }}</td>
                                        <td>{{ $permission->group_name ?? '—' }}</td>


                                        <td class="d-flex gap-2">
                                            <a href="{{ route('admin.permissions.edit',$permission->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.permissions.destroy', $permission->id) }}" class="btn btn-danger btn-sm deletebutton rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
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
