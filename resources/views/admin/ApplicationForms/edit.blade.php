@extends('backend.layouts.app')

@section('title', 'Edit Application Form')

@push('styles')
@endpush

@section('content')
<main class="main-wrapper">
     <div class=" container-fluid px-4 mt-4">

        <div class="bg-image" style="background-image: url('assets/media/photos/neilit.JPG'); min-height: 100px;">

            <div class="bg-gd-white-op-l">

                <div class="d-flex justify-content-between align-items-center content py-3">

                    <h3 class="text-black-75 text-center text-sm-start mb-0">

                        Application Forms

                    </h3>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb  px-3 py-2 mb-0">

                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Edit Application Form</li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

            <div class="block-header block-header-default d-flex justify-content-end">
                <a href="{{ route('admin.application-forms.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
   
  

  <div class="row mt-2">



                <div class="col-md-12 col-xl-12">

                    <div class="block block-rounded p-3">

                    <div class="block-header block-header-default pb-2">

                            <h3 class="block-title">Edit Application Form</h3>

                        </div> 

                        <div class="block-content p-2">

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">

                                    {{ session('success') }}

                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>

                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                                    {{ session('error') }}

                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>

                                </div>
                            @endif


          <form class="row g-3"
                method="POST"
                action="{{ route('admin.application-forms.update', $application_form) }}"
                enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-md-6">
              <label for="input13" class="form-label required">Form Name</label>
             
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       id="input13"
                       value="{{ old('name', $application_form->name) }}"
                       required
                       placeholder="Form Name">
               
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
             
            </div>

            <div class="col-md-6">
              <label for="input14" class="form-label required">Status</label>
              <div class="position-relative input-icon">
                <select name="is_active"
                        class="form-control @error('is_active') is-invalid @enderror"
                        id="input14"
                        required>
                  <option value="" disabled>Select Status</option>
                  <option value="1" {{ old('is_active', (int)$application_form->is_active) === 1 ? 'selected' : '' }}>Active</option>
                  <option value="0" {{ old('is_active', (int)$application_form->is_active) === 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
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
              @error('short_description')<div class="text-danger small">{{ $message }}</div>@enderror
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
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror

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
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                </div>

                @if($application_form->image)
                  <small class="text-muted d-block mt-1">Current file:
                    <code>{{ basename($application_form->image) }}</code>
                  </small>
                @endif
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex flex-wrap gap-2">

                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-save me-1"></i> Update
                </button>

                <a href="{{ route('admin.application-forms.index') }}" class="btn btn-secondary">
                  <i class="fa fa-times me-1"></i> Cancel
                </a>

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
