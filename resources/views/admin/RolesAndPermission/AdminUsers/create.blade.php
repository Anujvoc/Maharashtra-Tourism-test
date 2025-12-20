@extends('admin.layouts.app')

@section('title', 'Add Admin Users')

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
              <li class="breadcrumb-item active" aria-current="page">Add Admin Users</li>
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

      <div class="col-12 col-xl-12">
        <div class="card border-top border-3 border-danger rounded-0">
            <div class="card-body p-4">
                <h5 class="mb-4">Add Admin Users</h5>

                <form class="row g-3" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <div class="position-relative input-icon">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="User Name"
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
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
                                   value="{{ old('phone') }}"
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

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative input-icon">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter Password"
                                   required>
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="material-icons-outlined fs-5">lock</i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    {{-- <div class="col-md-6">
                        <label for="roleSelect" class="form-label">Select Role</label>
                        <div class="position-relative input-icon">
                            <select name="roles"
                                    id="roleSelect"
                                    class="form-select @error('roles') is-invalid @enderror"
                                    required>
                                <option disabled {{ old('roles') ? '' : 'selected' }}>Choose</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('roles') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('roles')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}

                    @php
                $oldRegion   = old('region_id');
                $oldDistrict = old('district_id');
                $oldRole     = old('roles');
            @endphp


                    <div class="col-md-6">
                        <label for="roleSelect" class="form-label required">Select Role</label>

                        <select name="roles"
                                id="roleSelect"
                                class="form-select @error('roles') is-invalid @enderror"
                                required>
                            <option disabled {{ $oldRole ? '' : 'selected' }}>Choose</option>

                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $oldRole == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>




                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <div class="position-relative input-icon">
                            <select name="is_visible"
                                    id="status"
                                    class="form-control @error('is_visible') is-invalid @enderror"
                                    required>
                                <option value="" disabled {{ old('is_visible') ? '' : 'selected' }}>Select Status</option>
                                <option value="active"   {{ old('is_visible', 'active')   == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('is_visible', 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <span class="position-absolute top-50 translate-middle-y">
                                <i class="bi bi-toggle-on"></i>
                            </span>
                            @error('is_visible')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4" id="regionRow" style="display:none;">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label required">
                                    <i class="bi bi-map"></i> Select Region
                                </label>

                                <select id="region_id"
                                        name="region_id" required
                                        class="form-control @error('region_id') is-invalid @enderror"
                                        onchange="get_Region_District(this.value)">
                                    <option value="">Select Region</option>

                                    @foreach($regions as $r)
                                        <option value="{{ $r->id }}"
                                            {{ $oldRegion == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('region_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label required">
                                    <i class="bi bi-geo"></i> Select District
                                </label>

                                <select id="district_id"
                                        name="district_id" required
                                        class="form-control @error('district_id') is-invalid @enderror">
                                    <option value="">Select District</option>
                                </select>

                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>




                    {{-- Buttons --}}
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-grd-primary px-4">Create User</button>

                            <a href="{{ route('admin.users.create') }}" class="btn btn-grd-royal px-4">
                                Reset
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
<script>
    $(document).ready(function () {

        const DY_DIRECTOR_ROLE_ID = '11';

        const $roleSelect   = $('#roleSelect');
        const $regionRow    = $('#regionRow');
        const $regionSelect = $('#region_id');
        const $districtSelect = $('#district_id');

        const OLD_ROLE     = "{{ $oldRole }}";
        const OLD_REGION   = "{{ $oldRegion }}";
        const OLD_DISTRICT = "{{ $oldDistrict }}";

        // 🔹 Show / Hide Region Row based on Role
        function toggleRegionRow() {

            if ($roleSelect.val() === DY_DIRECTOR_ROLE_ID) {

                $regionRow.slideDown();

                // old region hai aur district empty hai → load districts
                if (OLD_REGION && $districtSelect.children('option').length <= 1) {
                    loadDistricts(OLD_REGION, OLD_DISTRICT);
                }

            } else {
                $regionRow.slideUp();
            }
        }

        // 🔹 Load districts via AJAX
        function loadDistricts(regionId, selectedDistrict = null) {

            if (!regionId) {
                $districtSelect.html('<option value="">Select District</option>');
                return;
            }

            const url = "{{ route('get_Region_District', ['id' => ':id']) }}"
                .replace(':id', regionId);

            $districtSelect.html('<option value="">Loading...</option>');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (resp) {

                    let options = '<option value="">Select District</option>';

                    if (Array.isArray(resp)) {
                        $.each(resp, function (index, item) {
                            let selected = (selectedDistrict == item.id) ? 'selected' : '';
                            options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                        });
                    }

                    $districtSelect.html(options);
                },
                error: function () {
                    $districtSelect.html('<option value="">Error loading districts</option>');
                }
            });
        }

        // 🔹 EVENTS
        $roleSelect.on('change', toggleRegionRow);

        $regionSelect.on('change', function () {
            loadDistricts($(this).val());
        });

        // 🔹 Initial load (page reload / validation fail)
        toggleRegionRow();
    });
    </script>

@endpush


