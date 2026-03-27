@extends('backend.partial.master')
@section('title', 'Adventure Activity')
@section('backend-content')

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
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title m-0">{{ isset($activity) ? 'Edit Activity' : 'Add Adventure Activity' }}</h4>
            </div>

            <form action="{{ isset($activity) 
                ? route('adventure-activity.update', $activity->id) 
                : route('adventure-activity.store') }}" method="post">

                @csrf
                @if(isset($activity)) @method('PUT') @endif

                <div class="card-body">

                    <div class="form-group">
                        <label>Category *</label>
                        <select name="adventure_activity_categories_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('adventure_activity_categories_id', $activity->adventure_activity_categories_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $activity->name ?? '') }}">
                    </div>

                </div>

                <div class="card-body">
                    <button class="btn btn-primary">
                        {{ isset($activity) ? 'Update' : 'Submit' }}
                    </button>

                    @if(isset($activity))
                        <a href="{{ route('adventure-activity.index') }}" class="btn btn-secondary">Cancel</a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- ===== Table ===== -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title m-0">Activity List</h4></div>
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
                            @forelse($activities as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->category->name ?? '-' }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="{{ route('adventure-activity.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('adventure-activity.destroy', $item->id) }}"
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