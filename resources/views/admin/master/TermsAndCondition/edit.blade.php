@extends('admin.layouts.app')

@section('title', 'Edit Terms And Condition')

@push('styles')
    <!-- include Summernote CSS if not already included globally -->
    <link rel="stylesheet" href="{{ asset('frontend/backend/assets/modules/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Terms And Condition</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Terms And Condition</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.TermsAndCondition.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
      </div>

      <div class="col-12 col-xl-12">
        <div class="card border-top border-3 border-danger rounded-0">
          <div class="card-body p-4">
            <h5 class="mb-4">Edit Terms And Condition</h5>

            <form class="row g-3" method="POST" action="{{ route('admin.TermsAndCondition.update', $TermsAndCondition->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="form_id" class="form-label">Forms</label>
                    <div class="position-relative input-icon">
                        <select name="form_id" id="form_id"
                            class="form-control @error('form_id') is-invalid @enderror">
                            <option value="">-- Select Form --</option>

                            @foreach($forms as $form)
                                <option value="{{ $form->id }}"
                                    {{ (string) old('form_id', (string) $TermsAndCondition->form_id) === (string) $form->id ? 'selected' : '' }}>
                                    {{ $form->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('form_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="is_active" class="form-label">Status</label>
                    <div class="position-relative input-icon">
                        <select name="is_active" class="form-control @error('is_active') is-invalid @enderror" id="is_active" required>
                            <option value="" disabled {{ old('is_active', '') === '' ? 'selected' : '' }}>Select Status</option>
                            <option value="1" {{ (string) old('is_active', (string) $TermsAndCondition->is_active) === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (string) old('is_active', (string) $TermsAndCondition->is_active) === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-toggle-on"></i></span>
                        @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description"
                        class="form-control summernote @error('description') is-invalid @enderror">{!! old('description', $TermsAndCondition->description ?? '') !!}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Optional: If you have an image input in edit, place it here.
                     The preview JS below will only run if #imageInput exists. -->
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-grd-primary px-4">Update</button>
                        <a href="{{ route('admin.TermsAndCondition.create') }}" class="btn btn-grd-royal px-4">Reset</a>
                    </div>
                </div>
            </form>

          </div>
        </div>
      </div>
  </main>
@endsection

@push('scripts')
    <!-- include Summernote JS if not already included globally -->
    <script src="{{ asset('frontend/backend/assets/modules/summernote/summernote-bs4.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Summernote (only if .summernote exists)
        if (document.querySelectorAll('.summernote').length) {
            $('.summernote').summernote({
                height: 200,
                tabsize: 2
            });
        }

        // Image preview logic — guarded so it only runs if input exists (prevents console errors)
        const input = document.getElementById('imageInput');
        if (input) {
            const previewWrapper = document.getElementById('previewWrapper');
            const previewImage = document.getElementById('previewImage');
            const removeBtn = document.getElementById('removeImageBtn');

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const maxSize = 2 * 1024 * 1024; // 2 MB
                if (file.size > maxSize) {
                    alert('The image exceeds 2 MB size limit.');
                    input.value = '';
                    if (previewWrapper) previewWrapper.classList.add('d-none');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    if (previewImage) {
                        previewImage.src = event.target.result;
                        if (previewWrapper) previewWrapper.classList.remove('d-none');
                    }
                };
                reader.readAsDataURL(file);
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function () {
                    input.value = '';
                    if (previewImage) previewImage.src = '#';
                    if (previewWrapper) previewWrapper.classList.add('d-none');
                });
            }
        }
    });
    </script>
@endpush
