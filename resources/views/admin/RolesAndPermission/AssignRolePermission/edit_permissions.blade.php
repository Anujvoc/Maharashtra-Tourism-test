@extends('admin.layouts.app')

@section('title', 'Edit Role Permission')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
  <div class="main-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Role Permission</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Role Permission</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">
          <a href="{{ route('admin.all.roles.permission') }}" class="btn btn-danger px-4">
            <i class="bi bi-arrow-left me-2"></i>Back
          </a>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-12">
      <div class="card border-top border-3 border-danger rounded-0">
        <div class="card-body p-4">
          <h5 class="mb-4">Edit Role Permission</h5>

          <form class="row g-3" method="POST" action="{{ route('admin.role.permission.update', $roles->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Keep the role visible but not editable --}}
            <input type="hidden" name="role_id" value="{{ $roles->id }}">
            <div class="col-md-12">
              <label class="form-label">Role Name</label>
              <div class="position-relative input-icon">
                <select class="form-select" disabled>
                  <option selected value="{{ $roles->id }}">{{ $roles->name }}</option>
                </select>
              </div>
            </div>

            <div class="row mb-3 mt-2">
              <div class="col-12">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" id="selectAllPermissions" type="checkbox">
                  <label class="form-check-label fw-bold" for="selectAllPermissions">Select All Permissions</label>
                </div>
              </div>
            </div>

            <div class="card-body table-responsive">
              <table id="datatable-permissions" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead class="table-dark">
                  <tr>
                    <th width="5%">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllGroup">
                      </div>
                    </th>
                    <th width="25%">Permission Group</th>
                    <th width="70%">Permissions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($permission_groups as $group)
                    @php
                      $permissionsByGroup = App\Models\User::get_permissionByGroupName($group->group_name);
                    @endphp
                    <tr>
                      <td>
                        <div class="form-check">
                          <input class="form-check-input group-checkbox" type="checkbox" data-group="{{ $group->group_name }}">
                        </div>
                      </td>
                      <td>
                        <label class="form-check-label fw-bold">{{ $group->group_name }}</label>
                      </td>
                      <td>
                        <div class="row">
                          @foreach($permissionsByGroup as $permission)
                            <div class="col-md-4 mb-2">
                              <div class="form-check">
                                <input
                                  class="form-check-input permission-checkbox"
                                  name="permission[]"
                                  type="checkbox"
                                  value="{{ $permission->name }}"
                                  data-group="{{ $group->group_name }}"
                                  id="permission_{{ $permission->id }}"
                                  {{ $roles->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                  {{ $permission->name }}
                                </label>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="col-md-12">
              <div class="d-md-flex d-grid align-items-center gap-3">
                <button type="submit" class="btn btn-grd-primary px-4">Update & Continue</button>
                <button type="reset" class="btn btn-grd-royal px-4">Reset</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>

@endsection

@push('scripts')
<script>
$(function() {
  // If you actually need DataTables, initialize it here.
  // Otherwise you can remove this init block entirely.
  if ($.fn.DataTable) {
    $('#datatable-permissions').DataTable({
      paging: false,
      searching: false,
      info: false,
      ordering: false,
      autoWidth: false,
      responsive: true
    });
  }

  // Select all permissions
  $('#selectAllPermissions').on('click', function() {
    const isChecked = $(this).prop('checked');
    $('.permission-checkbox').prop('checked', isChecked);
    $('.group-checkbox').prop('checked', isChecked);
    $('#selectAllGroup').prop('checked', isChecked);
  });

  // Select all groups (line-level)
  $('#selectAllGroup').on('click', function() {
    const isChecked = $(this).prop('checked');
    $('.group-checkbox').prop('checked', isChecked);
    $('.permission-checkbox').prop('checked', isChecked);
    $('#selectAllPermissions').prop('checked', isChecked);
  });

  // Group checkbox toggles all in that group
  $(document).on('click', '.group-checkbox', function() {
    const group = $(this).data('group');
    const isChecked = $(this).prop('checked');
    $('.permission-checkbox[data-group="'+group+'"]').prop('checked', isChecked);
    updateSelectAllState();
  });

  // Individual permissions update group + global states
  $(document).on('click', '.permission-checkbox', function() {
    const group = $(this).data('group');
    const allInGroupChecked = $('.permission-checkbox[data-group="'+group+'"]').length === $('.permission-checkbox[data-group="'+group+'"]:checked').length;
    $('.group-checkbox[data-group="'+group+'"]').prop('checked', allInGroupChecked);
    updateSelectAllState();
  });

  function updateSelectAllState() {
    const allPerms = $('.permission-checkbox').length;
    const checkedPerms = $('.permission-checkbox:checked').length;
    const allGroups = $('.group-checkbox').length;
    const checkedGroups = $('.group-checkbox:checked').length;

    $('#selectAllPermissions').prop('checked', allPerms > 0 && checkedPerms === allPerms);
    $('#selectAllGroup').prop('checked', allGroups > 0 && checkedGroups === allGroups);
  }

  // Initialize checkbox states on load
  // (sets group and global checkboxes based on which permissions are initially checked)
  $('.group-checkbox').each(function() {
    const group = $(this).data('group');
    const allInGroupChecked = $('.permission-checkbox[data-group="'+group+'"]').length === $('.permission-checkbox[data-group="'+group+'"]:checked').length;
    $(this).prop('checked', allInGroupChecked);
  });
  updateSelectAllState();
});
</script>
@endpush
