@extends('admin.layouts.app')

@section('title', 'Assign Role')

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
              <li class="breadcrumb-item active" aria-current="page">Add Role Permission</li>
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
                            <h5 class="mb-4">Add Role Permission</h5>
                            <form class="row g-3" method="POST" action="{{ route('admin.role.permission.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <label for="input13" class="form-label">Role Name</label>
                                    <div class="position-relative input-icon">
                                        <select class="form-select" aria-label="Default select example" class="form-control @error('name') is-invalid @enderror" required name="role_id" id="roleSelect">
                                            <option disabled {{ old('role_id') ? '' : 'selected' }}>Choose</option>
                                            @foreach($roles as $role)
                                            <option value="{{ $role->id ?? '' }}">{{ $role->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="row mb-3 mt-2">
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" id="selectAllPermissions" type="checkbox">
                                            <label class="form-check-label fw-bold">Select All Permissions</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                $permissions = App\Models\User::get_permissionByGroupName($group->group_name);
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
                                                        @foreach($permissions as $permission)
                                                        <div class="col-md-4 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input permission-checkbox" name="permission[]" type="checkbox" value="{{ $permission->id }}" data-group="{{ $group->group_name }}" id="permission_{{ $permission->id }}">
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
                                        <button type="submit" class="btn btn-grd-primary px-4">Save & Continue</button>
                                        <a href="{{ route('admin.roles.create') }}">
                                        <button type="button" class="btn btn-grd-royal px-4">Reset</button></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
      </div>
  </main>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
        // Initialize DataTable
        var table = $('#permissionsTable').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": false,
            "autoWidth": false,
            "responsive": true
        });

        // Select all permissions
        $('#selectAllPermissions').click(function() {
            var isChecked = $(this).prop('checked');
            $('.permission-checkbox').prop('checked', isChecked);
            $('.group-checkbox').prop('checked', isChecked);
            $('#selectAllGroup').prop('checked', isChecked);
        });

        // Select all groups
        $('#selectAllGroup').click(function() {
            var isChecked = $(this).prop('checked');
            $('.group-checkbox').prop('checked', isChecked);
            $('.permission-checkbox').prop('checked', isChecked);
            $('#selectAllPermissions').prop('checked', isChecked);
        });

        // Group checkbox functionality
        $('.group-checkbox').click(function() {
            var groupName = $(this).data('group');
            var isChecked = $(this).prop('checked');

            // Check/uncheck all permissions in this group
            $('.permission-checkbox[data-group="' + groupName + '"]').prop('checked', isChecked);

            // Update select all checkbox state
            updateSelectAllState();
        });

        // Permission checkbox functionality
        $('.permission-checkbox').click(function() {
            var groupName = $(this).data('group');

            // Update group checkbox state
            var allChecked = true;
            $('.permission-checkbox[data-group="' + groupName + '"]').each(function() {
                if (!$(this).prop('checked')) {
                    allChecked = false;
                    return false;
                }
            });

            $('.group-checkbox[data-group="' + groupName + '"]').prop('checked', allChecked);

            // Update select all checkbox state
            updateSelectAllState();
        });

        // Function to update select all checkbox state
        function updateSelectAllState() {
            var allPermissionsChecked = true;
            var allGroupsChecked = true;

            $('.permission-checkbox').each(function() {
                if (!$(this).prop('checked')) {
                    allPermissionsChecked = false;
                    return false;
                }
            });

            $('.group-checkbox').each(function() {
                if (!$(this).prop('checked')) {
                    allGroupsChecked = false;
                    return false;
                }
            });

            $('#selectAllPermissions').prop('checked', allPermissionsChecked);
            $('#selectAllGroup').prop('checked', allGroupsChecked);
        }
    });
</script>
@endpush
