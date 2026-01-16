@extends('admin.layouts.app')

@section('title', 'Edit Project Category')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Project Category</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Project Category</li>
                    </ol>
                </nav>
            </div>

            <div class="ms-auto">
                <a href="{{ route('admin.projectCategory.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
                <div class="card-body p-4">
                    <h5 class="mb-4">Edit Project Category</h5>

                    <form class="row g-3"
                          method="POST"
                          action="{{ route('admin.projectCategory.update', $projectCategory->id) }}"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PUT') <!-- Use PUT/PATCH if your route uses it -->

                        <!-- Division Name -->
                        <div class="col-md-6">
                            <label class="form-label">Project Category</label>
                            <div class="position-relative input-icon">
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $projectCategory->name) }}"
                                       required
                                       placeholder="Enter Project Category">

                                <span class="position-absolute top-50 translate-middle-y">
                                    <i class="material-icons-outlined fs-5">business</i>
                                </span>

                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    
                        <div class="col-md-12">
                            <label class="form-label">Select type of unit(s)</label>

                            @php
                                // Old input OR DB values (converted to array)
                                $selectedUnits = old('units',
                                    is_string($projectCategory->units)
                                        ? json_decode($projectCategory->units, true)
                                        : ($projectCategory->units ?? [])
                                ) ?? [];
                             
                            @endphp

                            <div class="border rounded p-2" style="max-height: 160px; overflow-y:auto;">
                                <div class="row">
                                    @foreach ($units as $unit)
                                        <div class="col-md-2 col-4 mb-2">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    name="units[]"
                                                    value="{{ $unit->id }}"
                                                    id="unit_{{ $unit->id }}"
                                                    {{ in_array($unit->id, $selectedUnits) ? 'checked' : '' }}
                                                >

                                                <label class="form-check-label" for="unit_{{ $unit->id }}">
                                                    {{ $unit->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @error('areas')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>



                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="position-relative input-icon">
                                <select name="is_active"
                                        class="form-control @error('is_active') is-invalid @enderror"
                                        required>
                                    <option value="1" {{ old('is_active', $projectCategory->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $projectCategory->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>

                                <span class="position-absolute top-50 translate-middle-y">
                                    <i class="bi bi-toggle-on"></i>
                                </span>

                                @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-grd-primary px-4">Update</button>
                                <a href="{{ route('admin.projectCategory.edit', $projectCategory->id) }}" class="btn btn-grd-royal px-4">Reset</a>
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
    const input = document.getElementById('imageInput');
    const previewWrapper = document.getElementById('previewWrapper');
    const previewImage = document.getElementById('previewImage');
    const removeBtn = document.getElementById('removeImageBtn');

    if (input) {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const maxSize = 2 * 1024 * 1024;
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

        removeBtn.addEventListener('click', function () {
            input.value = '';
            previewImage.src = '#';
            previewWrapper.classList.add('d-none');
        });
    }
});
</script>
@endpush
