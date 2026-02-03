@extends('admin.layouts.app')

@section('title', 'Edit Area')

@push('styles')
@endpush

@section('content')
<main class="main-wrapper">
    <div class="main-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Area for classification</div>

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bx bx-home-alt"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit Area for classification
                        </li>
                    </ol>
                </nav>
            </div>

            {{-- Back button (view permission enough) --}}
            @can('view ProvisionalArea')
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('admin.master.area.index') }}"
                       class="btn btn-danger px-4">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
            @endcan
        </div>

        {{-- Card --}}
        <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-warning rounded-0">
                <div class="card-body p-4">
                    <h5 class="mb-4">Edit Area for classification</h5>

                    {{-- FORM: Only editable if user has edit permission --}}
                    @can('edit ProvisionalArea')
                    <form class="row g-3"
                          method="POST"
                          action="{{ route('admin.master.area.update', $area->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    @else
                    {{-- Read-only fallback --}}
                    <form class="row g-3">
                    @endcan

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label">Area for classification Name</label>
                            <div class="position-relative input-icon">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name', $area->name) }}"
                                       @cannot('edit ProvisionalArea') disabled @endcannot>
                                <span class="position-absolute top-50 translate-middle-y">
                                    <i class="material-icons-outlined fs-5">person_outline</i>
                                </span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="position-relative input-icon">
                                <select name="is_active"
                                        class="form-select"
                                        @cannot('edit ProvisionalArea') disabled @endcannot>
                                    <option value="1" {{ $area->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $area->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <span class="position-absolute top-50 translate-middle-y">
                                    <i class="bi bi-toggle-on"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">

                                @can('edit ProvisionalArea')
                                <button type="submit" class="btn btn-warning px-4">
                                    Update
                                </button>
                                @endcan

                                @can('edit ProvisionalArea')
                                <a href="{{ route('admin.master.area.edit', $area->id) }}"
                                   class="btn btn-grd-royal px-4">
                                    Reset
                                </a>
                                @endcan
                            </div>

                            @cannot('edit ProvisionalArea')
                                <div class="alert alert-warning mt-3">
                                    You do not have permission to update this Area.
                                </div>
                            @endcannot
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
// Optional JS
</script>
@endpush
