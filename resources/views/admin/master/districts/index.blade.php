@extends('admin.layouts.app')

@section('title', 'District')

@push('styles')
@endpush

@section('content')
<main class="main-wrapper">
  <div class="main-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">District</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Index</li>
          </ol>
        </nav>
      </div>
    </div>

    {{-- Search + Buttons --}}
    <div class="row g-3">
      <div class="col-auto">
        <div class="position-relative">
          <form method="GET">
            <input class="form-control ps-5" type="search" name="q" value="{{ $q ?? '' }}" placeholder="Search District or State...">
            <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
          </form>
        </div>
      </div>

      <div class="col-auto ms-auto">
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-filter px-4" type="button">
            <i class="bi bi-box-arrow-right me-2"></i>Export
          </button>
          <a href="{{ route('admin.master.districts.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-2"></i>Add District
          </a>
        </div>
      </div>
    </div>

    <div class="card mt-2">
      <div class="card-body">
        <div class="table-responsive mt-2">
          <table id="example" class="table table-striped table-bordered align-middle">
            <thead>
              <tr>
                <th>District Name</th>
                <th>State</th>
                <th>Country</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($districts as $district)
                <tr>
                  <td>{{ $district->name }}</td>
                  <td>{{ $district->state->name ?? '-' }}</td>
                  <td>{{ $district->state->country->name ?? '-' }}</td>
                  <td>
                    <span class="badge bg-{{ $district->is_active ? 'success' : 'secondary' }}">
                      {{ $district->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.master.districts.edit', $district->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST"
                          action="{{ route('admin.master.districts.destroy', $district->id) }}"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No district found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
// custom JS if needed
</script>
@endpush
