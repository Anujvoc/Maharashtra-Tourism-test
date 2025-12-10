@extends('admin.layouts.app')

@section('title', 'Add Permission')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Permission</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Permission</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                  </a>
            </div>
        </div>
      </div>

      <div class="col-12 col-xl-12">
        <div class="card border-top border-3 border-danger rounded-0">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Add Permission</h5>
                            <form class="row g-3" method="POST" action="{{ route('admin.permissions.store') }}">
                                @csrf
                                <div class="col-md-6">
                                    <label for="input13" class="form-label">Permission Name</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="input13"  value="{{ old('name') }}" required placeholder="Permission Name">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="material-icons-outlined fs-5">person_outline</i></span>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input14" class="form-label">Group Name</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" name="group_name" class="form-control  @error('group_name') is-invalid @enderror"  value="{{ old('group_name') }}" id="input14" required placeholder="Group Name">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-diagram-3-fill"></i></span>
                                        @error('group_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>






                                {{-- <div class="col-md-4">
                                    <label for="input21" class="form-label">State</label>
                                    <select id="input21" class="form-select">
                                        <option selected="">Choose...</option>
                                        <option>One</option>
                                        <option>Two</option>
                                        <option>Three</option>
                                    </select>
                                </div> --}}



                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-grd-primary px-4">Create Permission</button>
                                        <a href="{{ route('admin.permissions.create') }}">
                                        <button type="button" class="btn btn-grd-royal px-4">Reset</button></a>
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

</script>
@endpush
