@extends('admin.layouts.app')

@section('title', 'Edit Application Form')

@push('styles')
@endpush

@section('content')
<main class="main-wrapper">
  <div class="main-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Application Forms</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Application Form</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">
          <a href="{{ route('admin.application-forms.index') }}" class="btn btn-danger px-4">
            <i class="bi bi-arrow-left me-2"></i>Back
          </a>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-12">
      <div class="card border-top border-3 border-danger rounded-0">
        <div class="card-body p-4">
          <h5 class="mb-4">Edit Application Form</h5>

          <form class="row g-3"
                method="POST"
                action="{{ route('admin.application-forms.update', $application_form) }}"
                enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-md-6">
              <label for="input13" class="form-label">Form Name</label>
              <div class="position-relative input-icon">
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       id="input13"
                       value="{{ old('name', $application_form->name) }}"
                       required
                       placeholder="Form Name">
                <span class="position-absolute top-50 translate-middle-y"><i class="material-icons-outlined fs-5">person_outline</i></span>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="col-md-6">
              <label for="input14" class="form-label">Status</label>
              <div class="position-relative input-icon">
                <select name="is_active"
                        class="form-control @error('is_active') is-invalid @enderror"
                        id="input14"
                        required>
                  <option value="" disabled>Select Status</option>
                  <option value="1" {{ old('is_active', (int)$application_form->is_active) === 1 ? 'selected' : '' }}>Active</option>
                  <option value="0" {{ old('is_active', (int)$application_form->is_active) === 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-toggle-on"></i></span>
                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="col-md-12">
              <label class="form-label">Short Description</label>
              <input type="text"
                     name="short_description"
                     class="form-control @error('short_description') is-invalid @enderror"
                     value="{{ old('short_description', $application_form->short_description) }}"
                     maxlength="100"
                     placeholder="Short description">
              @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
              <label class="form-label">Image <small class="text-muted">(max 2 MB)</small></label>

              <div class="position-relative" style="max-width:260px;">
                <!-- File input -->
                <input type="file"
                       name="image"
                       id="imageInput"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                <!-- Hidden flag to remove existing image on save -->
                <input type="hidden" name="remove_image" id="removeImageFlag" value="0">

                <!-- Preview container -->
                <div id="previewWrapper" class="mt-2 position-relative {{ ($application_form->image || old('image')) ? '' : 'd-none' }}">
                  <img id="previewImage"
                       src="{{ $application_form->image ? asset('storage/'.$application_form->image) : '#' }}"
                       alt="Preview"
                       style="width:100%;max-height:200px;object-fit:cover;border:1px solid #ddd;border-radius:8px;padding:2px;">

                  <!-- Delete / remove icon -->
                  <button type="button"
                          id="removeImageBtn"
                          class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                          title="Remove image"
                          style="border-radius:50%;width:28px;height:28px;line-height:1;">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>

                @if($application_form->image)
                  <small class="text-muted d-block mt-1">Current file:
                    <code>{{ basename($application_form->image) }}</code>
                  </small>
                @endif
              </div>
            </div>

            <div class="col-md-12">
              <div class="d-md-flex d-grid align-items-center gap-3">
                <button type="submit" class="btn btn-grd-primary px-4">Update</button>
                <a href="{{ route('admin.application-forms.index') }}" class="btn btn-grd-royal px-4">Cancel</a>
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
document.addEventListener('DOMContentLoaded', function () {
  const input          = document.getElementById('imageInput');
  const previewWrapper = document.getElementById('previewWrapper');
  const previewImage   = document.getElementById('previewImage');
  const removeBtn      = document.getElementById('removeImageBtn');
  const removeFlag     = document.getElementById('removeImageFlag');

  // initial state: if there is an existing image, show wrapper (already handled by Blade)
  // when user selects a new image
  input.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    // 2MB limit check
    const maxSize = 2 * 1024 * 1024; // 2 MB
    if (file.size > maxSize) {
      alert('The image exceeds 2 MB size limit.');
      input.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = function (event) {
      previewImage.src = event.target.result;    // show the newly picked image
      previewWrapper.classList.remove('d-none'); // ensure visible
      removeFlag.value = '0';                    // reset deletion flag when a new file is chosen
    };
    reader.readAsDataURL(file);
  });

  // remove button clicked -> clear selected file and mark for deletion
  removeBtn.addEventListener('click', function () {
    input.value = '';                       // clear file input (no new upload)
    previewImage.src = '#';                 // remove preview
    previewWrapper.classList.add('d-none'); // hide preview box
    removeFlag.value = '1';                 // tell backend to delete existing image
  });
});
</script>
@endpush
