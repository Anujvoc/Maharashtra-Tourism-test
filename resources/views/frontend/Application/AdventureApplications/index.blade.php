@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Adventure Applications</h4>
        <a href="{{ route('adventure-applications.create') }}" class="btn btn-primary">Create New</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Applicant</th>
                        <th>Mobile</th>
                        <th>Activity</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr id="row-{{ $app->id }}">
                        <td>{{ $loop->iteration + ($applications->currentPage()-1)*$applications->perPage() }}</td>
                        <td>{{ $app->applicant_name }} <br><small>{{ $app->company_name }}</small></td>
                        <td>{{ $app->mobile }}</td>
                        <td>{{ $app->adventure_category }} - {{ $app->activity_name }}</td>
                        <td>{{ $app->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('adventure-applications.edit', $app->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-url="{{ route('frontend.adventure-applications.destroy', $app->id) }}">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No records</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    // Delete with confirmation
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        const url = $(this).data('url');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the application',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {_method: 'DELETE'},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success(res){
                        Swal.fire('Deleted', res.message || 'Deleted', 'success');
                        // Optionally remove row
                        // find id from url (last segment)
                        const id = url.split('/').pop();
                        $('#row-'+id).remove();
                    },
                    error(){
                        Swal.fire('Error', 'Could not delete', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush

