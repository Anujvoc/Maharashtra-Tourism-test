@extends('backend.layouts.app')

@section('title', 'Countries')

@push('styles')
@endpush

@section('content')

<div class=" container-fluid px-4 mt-4">
    <div class="main-content">
      <!--breadcrumb-->
   


 <div class="bg-image" style="min-height: 100px;">
          <div class="bg-image" style="background-image: url('assets/media/photos/sugarcane.jpg'); min-height: 100px;"> 

        <div class="bg-gd-white-op-l">

            <div class="d-flex justify-content-between align-items-center content py-3">

                <h3 class="text-black-75 text-center text-sm-start mb-0">

                    Country Management

                </h3>

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb  px-3 py-2 mb-0">

                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Country</li>

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
            <i class="bi bi-plus-lg me-1"></i> Add Country
          </a>

        </div>
      </div>
--}}
    </div>

  </div>
</div>

				<div class="card mt-2">

					<div class="card-body">
             <div class="block-header block-header-default d-flex justify-content-between align-items-center">

                    <h3 class="block-title">

                        Country List

                    </h3>

                </div>

						<div class="block-content block-content-full overflow-x-auto">

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons" id="batch-table" style="width:100%">

								<thead>
									<tr>
                     <th class="text-center" style="width: 40px;">#</th>
                                        <th>Name</th>
                                        <th>ISO Code</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th class="no_action">Actions</th>
									</tr>
								</thead>
                                <tbody>
                                    @forelse ($countries as $key => $country)
                                      <tr>
                                        <td>{{ $key + 1  }}</td>
                                        <td>{{ $country->name }}</td>
                                        <td>{{ $country->iso_code ?? '—' }}</td>
                                        <td>
                                          <span class="badge bg-{{ $country->is_active ? 'success' : 'secondary' }}">
                                            {{ $country->is_active ? 'Active' : 'Inactive' }}
                                          </span>
                                        </td>
                                        <td>{{ $country->created_at->format('d M, Y') }}</td>
                                        <td class="d-flex gap-2">
                                            {{--
                                          <a href="{{ route('admin.master.countries.edit', $country) }}" class="btn btn-sm btn-warning">Edit</a>
                                          --}}
                                          <form method="POST" action="{{ route('admin.master.countries.destroy', $country) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                          </form>
                                        </td>
                                      </tr>
                                    @empty
                                      <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No countries found.</td>
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





