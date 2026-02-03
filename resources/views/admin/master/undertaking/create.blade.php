@extends('admin.layouts.app')

@section('title', 'Add Undertaking')

@push('styles')
@endpush

@section('content')
@php
$undertaking = DB::table('undertakings')->where('id',1)->first();
@endphp
<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Undertaking</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Undertaking</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="javascript:void(0);" style="background-color: rgb(174, 20, 180)"
                class="btn btn-warnin px-4 view-description"
                data-id="{{ $undertaking->id ?? $undertaking->id ?? '' }}">
                <i class="bi bi-eye me-2"></i> View
             </a>
                <a href="{{ route('admin.undertaking.index') }}" class="btn btn-danger px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back
                  </a>


            </div>
        </div>
      </div>


      <div class="col-12 col-xl-12">
        <div class="card border-top border-3 border-danger rounded-0">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Add Undertaking</h5>
                            <form class="row g-3" method="POST" action="{{ route('admin.undertaking.update',1) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description"
                                        class="form-control summernote @error('description') is-invalid @enderror">{!! old('description', $undertaking->description ?? '') !!}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="position-relative input-icon">
                                        <select name="is_active" class="form-control @error('is_active') is-invalid @enderror" id="is_active" required>
                                            <option value="" disabled {{ old('is_active', '') === '' ? 'selected' : '' }}>Select Status</option>
                                            <option value="1" {{ (string) old('is_active', (string) $undertaking->is_active) === '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ (string) old('is_active', (string) $undertaking->is_active) === '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bi bi-toggle-on"></i></span>
                                        @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-grd-primary px-4">Update</button>
                                        <a href="{{ route('admin.undertaking.create') }}" class="btn btn-grd-royal px-4">Reset</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
      </div>
  </main>
  <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="descriptionModalLabel">Full Description</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="descriptionModalBody"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection


@push('scripts')

<script>
 $(document).on('click', '.view-description', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        if (!id) return;
        let url = '{{ route('admin.undertaking.fullDescription', ['id' => '__ID__']) }}'.replace('__ID__', id);

        $.get(url, function (res) {
            if (res && res.html !== undefined) {
                $('#descriptionModalBody').html(res.html);
                var myModal = new bootstrap.Modal(document.getElementById('descriptionModal'));
                myModal.show();
            } else {
                alert('No description available.');
            }
        }).fail(function () {
            alert('Unable to load description. Try again.');
        });
    });
</script>


@endpush
