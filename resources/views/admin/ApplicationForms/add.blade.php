@extends('backend.layouts.app')

@section('title', 'Add Application Forms')

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

                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Add Application Forms</li>

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

                            <h3 class="block-title">Add Application Forms</h3>

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

                            <form class="row g-3" method="POST" action="{{ route('admin.application-forms.store') }}" enctype="multipart/form-data">
                                @csrf
                                 <div class="col-md-6">
                                <label class="form-label required">Form Name</label>
                                <input type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Enter form name">
                                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>


                                 <div class="col-md-6">
                    <label class="form-label required">Status</label>
                    <select name="is_active"
                            class="form-select @error('is_active') is-invalid @enderror">
                        <option value="">Select Status</option>
                        <option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>Active</option>
                        <option value="0" {{ old('is_active','1')=='0'?'selected':'' }}>Inactive</option>
                    </select>
                    @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <!-- Short Description -->
                <div class="col-md-12">
                    <label class="form-label">Short Description</label>
                    <input type="text"
                           name="short_description"
                           class="form-control @error('short_description') is-invalid @enderror"
                           value="{{ old('short_description') }}"
                           maxlength="100"
                           placeholder="Enter short description">
                    @error('short_description')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Image <small class="text-muted">(Max 2MB)</small></label>

                    <input type="file"
                           name="image"
                           id="imageInput"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/*">

                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror

                    <!-- Preview -->
                    <div id="previewWrapper" class="mt-3 d-none position-relative" style="max-width:200px;">
                        <img id="previewImage"
                             src="#"
                             class="img-fluid rounded border p-1">

                        <button type="button"
                                id="removeImageBtn"
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>


                           

                                <div class="col-12">
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check me-1"></i> Create
        </button>

        <a href="{{ route('admin.application-forms.create') }}" class="btn btn-secondary">
            <i class="fa-solid fa-rotate-right me-1"></i> Reset
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
