@extends('backend.partial.master')
@section('title', 'Applicant Registration Category')
@section('backend-content')

<div class="row">
    <!-- ================= Form ================= -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title m-0">
                    {{ isset($applicant) ? 'Edit Category' : 'Adventure Activity Category' }}
                </h4>
            </div>

            <form action="{{ isset($applicant) 
                ? route('adventure-activity-category.update', $applicant->id) 
                : route('adventure-activity-category.store') }}" 
                method="post">
                
                @csrf
                @if(isset($applicant))
                    @method('PUT')
                @endif

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>

                        <input type="text"
                               class="form-control"
                               name="name"
                               value="{{ old('name', $applicant->name ?? '') }}">

                        <span class="text-danger">
                            @error('name') {{ $message }} @enderror
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($applicant) ? 'Update' : 'Submit' }}
                    </button>

                    @if(isset($applicant))
                        <a href="{{ route('applicant-registration.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Table ================= -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title m-0">Category List</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped"  id="responsive-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th width="120" id="no-export">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($applicants as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>

                                    <!-- Edit -->
                                    <a href="{{ route('adventure-activity-category.edit', $item->id) }}"
                                    class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('adventure-activity-category.destroy', $item->id) }}"
                                        method="post"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Delete this item?')"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection