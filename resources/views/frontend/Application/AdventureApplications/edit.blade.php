@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Application</h4>

    <div id="formContainer">
        @include('Application.AdventureApplications.partials.form', [
            'application' => $application,
            'regions' => $regions ?? collect(),
            'districts' => $districts ?? collect(),
            'categories' => $categories ?? []
        ])
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    // reuse same submit logic used on create
    $(document).on('submit', '#adventureForm', function(e){
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success(res){
                Swal.fire('Success', res.message || 'Updated', 'success').then(()=> {
                    window.location.href = "{{ route('adventure-applications.index') }}";
                });
            },
            error(xhr){
                if (xhr.status === 422) {
                    const json = xhr.responseJSON || {};
                    if (json.form) $('#formContainer').html(json.form);
                    const errors = json.errors || xhr.responseJSON.errors || {};
                    let html = '';
                    $.each(errors, function(k, v){ html += v.join('<br>') + '<br>'; });
                    Swal.fire({title: 'Validation error', html: html || 'Please check form', icon: 'error'});
                } else {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            }
        });
    });
});
</script>
@endpush
