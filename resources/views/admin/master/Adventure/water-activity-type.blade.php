@extends('backend.partial.master')
@section('title', 'Water Activity Type')
@section('backend-content')

<!-- Flash Messages -->
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif

<div class="row">

    <!-- ===== Form ===== -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title m-0">
                    {{ isset($waterActivity) ? 'Edit Water Activity' : 'Add Water Activity' }}
                </h4>
            </div>

            <form action="{{ isset($waterActivity) 
                ? route('water-activity-type.update', $waterActivity->id) 
                : route('water-activity-type.store') }}" method="post">

                @csrf
                @if(isset($waterActivity)) @method('PUT') @endif

                <div class="card-body">

                    <!-- Category -->
                    <div class="form-group">
                        <label>Category *</label>
                        <select name="adventure_activity_categories_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('adventure_activity_categories_id', $waterActivity->adventure_activity_categories_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Name -->
                    <div class="form-group mt-2">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $waterActivity->name ?? '') }}">
                    </div>

                </div>

                <div class="card-body">
                    <button class="btn btn-primary">
                        {{ isset($waterActivity) ? 'Update' : 'Submit' }}
                    </button>

                    @if(isset($waterActivity))
                        <a href="{{ route('water-activity-type.index') }}" class="btn btn-secondary">Cancel</a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- ===== Table ===== -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4 class="card-title m-0">Water Activity List</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($waterActivities as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->category->name ?? '-' }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="{{ route('water-activity-type.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('water-activity-type.destroy', $item->id) }}"
                                        method="post" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data</td>
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