@extends('admin.layouts.app')

@section('title', 'Edit Caravan Type')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Caravan Type</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Caravan Type</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.master.types.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
      </div>

      <div class="col-12 col-xl-12">
        <div class="card border-top border-3 border-warning rounded-0">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit Caravan Type</h5>

                <form class="row g-3" method="POST" action="{{ route('admin.master.types.update', $caravan_type->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label for="input13" class="form-label">Caravan Type</label>
                        <div class="position-relative input-icon">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="input13" value="{{ old('name', $caravan_type->name) }}" required placeholder="Name">
                            <span class="position-absolute top-50 translate-middle-y"><i class="material-icons-outlined fs-5">person_outline</i></span>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="input14" class="form-label">Status</label>
                        <div class="position-relative input-icon">
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" id="input14" required>
                                <option value="1" {{ old('is_active', $caravan_type->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $caravan_type->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-toggle-on"></i></span>
                            @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-warning px-4">Update</button>
                            <a href="{{ route('admin.master.types.edit', $caravan_type->id) }}" class="btn btn-grd-royal px-4">Reset</a>
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
// If you want to enable preview or similar behavior, include JS here.
</script>
@endpush
