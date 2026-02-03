@extends('admin.layouts.app')

@section('title', 'Add Terms And Condition')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Terms And Condition </div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Terms And Condition</li>
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
                            <h5 class="mb-4">Add Terms And Condition</h5>
                            <form class="row g-3" method="POST" action="{{ route('admin.TermsAndCondition.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6">
                                    <label for="form_id" class="form-label">Forms</label>
                                    <div class="position-relative input-icon">
                                        <select name="form_id" id="form_id"
                                            class="form-control @error('form_id') is-invalid @enderror">

                                            <option value="">-- Select Form --</option>

                                            @foreach($forms as $form)
                                                <option value="{{ $form->id }}"
                                                    {{ old('form_id') == $form->id ? 'selected' : '' }}>
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
                                    <label for="input14" class="form-label">Status</label>
                                    <div class="position-relative input-icon">
                                        <select name="is_active" class="form-control @error('is_active') is-invalid @enderror" id="input14" required>
                                            <option value="" disabled {{ old('is_active', '1') === '' ? 'selected' : '' }}>Select Status</option>
                                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', '1') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-toggle-on"></i></span>
                                        @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>

                                    <textarea name="description" id="description"
                                        class="form-control summernote @error('description') is-invalid @enderror">
                                        {{ old('description') }}
                                    </textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-grd-primary px-4">Create</button>
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
