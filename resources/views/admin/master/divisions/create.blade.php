@extends('admin.layouts.app')

@section('title', 'Add Division')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Division</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Division</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.master.divisions.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                  </a>
            </div>
        </div>
      </div>

      <div class="col-12 col-xl-12">
  <div class="card border-top border-3 border-danger rounded-0">
    <div class="card-body p-4">
      <h5 class="mb-4">Add Division</h5>
      <form class="row g-3" method="POST" action="{{ route('admin.master.divisions.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Division Name -->
        <div class="col-md-6">
          <label for="input13" class="form-label">Division Name</label>
          <div class="position-relative input-icon">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
              id="input13" value="{{ old('name') }}" required placeholder="Enter Division Name">
            <span class="position-absolute top-50 translate-middle-y">
              <i class="material-icons-outlined fs-5">business</i>
            </span>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Division Code -->
        <div class="col-md-6">
          <label for="input15" class="form-label">Division Code</label>
          <div class="position-relative input-icon">
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
              id="input15" value="{{ old('code') }}" required placeholder="Enter Code (e.g., KON)">
            <span class="position-absolute top-50 translate-middle-y">
              <i class="bi bi-upc-scan"></i>
            </span>
            @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

       <!-- District Selection -->
<!-- District Selection -->
<div class="col-md-12">
    <label class="form-label">Select District(s)</label>

    <div class="border rounded p-2" style="max-height: 160px; overflow-y: auto;">
        <div class="row">
            @foreach ($districts as $district)
                <div class="col-md-2 col-4 mb-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="districts[]"
                            value="{{ $district->id }}"
                            id="district_{{ $district->id }}"
                            @if(is_array(old('districts')) && in_array($district->id, old('districts'))) checked @endif
                        >
                        <label class="form-check-label" for="district_{{ $district->id }}">
                            {{ $district->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @error('districts')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>



        <!-- Status -->
        <div class="col-md-6">
          <label for="input14" class="form-label">Status</label>
          <div class="position-relative input-icon">
            <select name="is_active" class="form-control @error('is_active') is-invalid @enderror" id="input14" required>
              <option value="" disabled {{ old('is_active', '1') === '' ? 'selected' : '' }}>Select Status</option>
              <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('is_active', '1') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-toggle-on"></i></span>
            @error('is_active')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Buttons -->
        <div class="col-md-12">
          <div class="d-md-flex d-grid align-items-center gap-3">
            <button type="submit" class="btn btn-grd-primary px-4">Create</button>
            <a href="{{ route('admin.master.divisions.create') }}" class="btn btn-grd-royal px-4">Reset</a>
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
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('imageInput');
    const previewWrapper = document.getElementById('previewWrapper');
    const previewImage = document.getElementById('previewImage');
    const removeBtn = document.getElementById('removeImageBtn');

    // when user selects an image
    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        // 2MB limit check
        const maxSize = 2 * 1024 * 1024; // 2 MB
        if (file.size > maxSize) {
            alert('The image exceeds 2 MB size limit.');
            input.value = '';
            previewWrapper.classList.add('d-none');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            previewImage.src = event.target.result;
            previewWrapper.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });

    // remove button clicked
    removeBtn.addEventListener('click', function () {
        input.value = '';                      // clear file input
        previewImage.src = '#';                // reset preview
        previewWrapper.classList.add('d-none'); // hide preview box
    });
});
</script>


@endpush
