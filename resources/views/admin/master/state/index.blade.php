@extends('backend.layouts.app')

@section('title', 'State')

@push('styles')
@endpush

@section('content')

<main class="main-wrapper">
  <div class=" container-fluid px-4 mt-4">
    <div class="main-content">
      <!--breadcrumb-->
       <div class="bg-image" style="min-height: 100px;">
          <div class="bg-image" style="background-image: url('assets/media/photos/sugarcane.jpg'); min-height: 100px;"> 

        <div class="bg-gd-white-op-l">

            <div class="d-flex justify-content-between align-items-center content py-3">

                <h3 class="text-black-75 text-center text-sm-start mb-0">

                    Stats Management

                </h3>

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb  px-3 py-2 mb-0">

                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>

                        <li class="breadcrumb-item active" aria-current="page">State</li>

                    </ol>

                </nav>

            </div>

        </div>

    </div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">

    <div class="row align-items-center g-3">

  
      <div class="col-lg-5 col-md-6">
        <form method="GET">
          <div class="position-relative">
            <input 
              type="search" 
              name="q" 
              value="{{ $q ?? '' }}" 
              class="form-control rounded-pill ps-5"
              placeholder="Search country..."
            >
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
              <i class="bi bi-search"></i>
            </span>
          </div>
        </form>
      </div>

      {{-- 
      <!-- Buttons -->
      <div class="col-lg-4 col-md-6 text-md-end">
        <div class="d-flex gap-2 justify-content-md-end flex-wrap">

          <button class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-box-arrow-down me-1"></i> Export
          </button>

          <a href="#" class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add State
          </a>

        </div>
      </div>
--}}
    </div>

  </div>
</div>





				<div class="card mt-2">

					<div class="card-body">

							<div class="block-content block-content-full overflow-x-auto">

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons" id="" style="width:100%">

								<thead>
									<tr>
                    <th class="text-center" style="width: 40px;">#</th>
                                        <th>State Name</th>
                                        <th>State Code</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th class="no_action">Actions</th>
									</tr>
								</thead>
                                <tbody>
                                    @forelse($states as $key => $state)
                                      <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ $state->code ?? '—' }}</td>
                                        <td>{{ $state->country->name ?? '-' }}</td>
                                        <td>
                                          <span class="badge bg-{{ $state->is_active ? 'success' : 'secondary' }}">
                                            {{ $state->is_active ? 'Active' : 'Inactive' }}
                                          </span>
                                        </td>

                                        <td class="d-flex gap-2">
                                            {{--
                                          <a href="{{ route('admin.master.states.edit', $state->id) }}" class="btn btn-sm btn-warning">Edit</a> --}}
                                          <form method="POST" action="{{ route('admin.master.states.destroy', $state->id) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                          </form>
                                        </td>
                                      </tr>
                                    @empty
                                      <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No state found.</td>
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
// Optional custom JS logic can go here
</script>
@endpush
