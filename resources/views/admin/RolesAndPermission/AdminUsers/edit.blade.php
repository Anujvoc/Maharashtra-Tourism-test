@extends('admin.layouts.app')

@section('title', 'Edit Admin User')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin Users</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Edit Admin User</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
      </div>

      @php
        // single role id for this user (Spatie)
        $userRoleId = $user->roles->pluck('id')->first();
      @endphp

      <div class="col-12 col-xl-12">
        <div class="card border-top border-3 border-danger rounded-0">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit Admin User</h5>

                <form class="row g-3" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <div class="position-relative input-icon">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="User Name"
                                   value="{{ old('name', $user->name) }}"
                                   pattern="^[A-Za-z\s]+$"
                                   title="Only letters and spaces are allowed"
                                   required
                                   onkeypress="return validateName(event)">
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="material-icons-outlined fs-5">person_outline</i>
                            </span>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <div class="position-relative input-icon">
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter Email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="material-icons-outlined fs-5">email</i>
                            </span>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <div class="position-relative input-icon">
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="Enter phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   pattern="^[6-9][0-9]{9}$"
                                   maxlength="10"
                                   onkeypress="return validateWhatsAppInput(event)"
                                   required>
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="material-icons-outlined fs-5">phone_iphone</i>
                            </span>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Password (optional on edit) --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Password
                            <small class="text-muted">(Leave blank to keep current)</small>
                        </label>
                        <div class="position-relative input-icon">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter New Password (optional)">
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="material-icons-outlined fs-5">lock</i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <label for="roleSelect" class="form-label">Select Role</label>
                        <div class="position-relative input-icon">
                            <select name="roles"
                                    id="roleSelect"
                                    class="form-select @error('roles') is-invalid @enderror"
                                    required>
                                <option disabled {{ old('roles', $userRoleId) ? '' : 'selected' }}>Choose</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('roles', $userRoleId) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <div class="position-relative input-icon">
                            <select name="is_visible"
                                    id="status"
                                    class="form-control @error('is_visible') is-invalid @enderror"
                                    required>
                                <option value="" disabled {{ old('is_visible', $user->is_visible) ? '' : 'selected' }}>
                                    Select Status
                                </option>
                                <option value="active"
                                    {{ old('is_visible', $user->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('is_visible', $user->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="bi bi-toggle-on"></i>
                            </span>
                            @error('is_visible')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-grd-primary px-4">Update User</button>

                            <a href="{{ route('admin.users.index') }}" class="btn btn-grd-royal px-4">
                                Cancel
                            </a>
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
    function validateWhatsAppInput(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const currentValue = event.target.value;

        // only digits 0-9
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
            return false;
        }

        // first digit 6-9
        if (currentValue.length === 0 && (charCode < 54 || charCode > 57)) {
            event.preventDefault();
            return false;
        }

        // max 10 digits
        if (currentValue.length >= 10) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function validateName(event) {
        const charCode = event.which ? event.which : event.keyCode;

        if (
            (charCode >= 65 && charCode <= 90) ||   // A-Z
            (charCode >= 97 && charCode <= 122) ||  // a-z
            charCode === 32                         // space
        ) {
            return true;
        }

        event.preventDefault();
        return false;
    }
</script>
@endpush
