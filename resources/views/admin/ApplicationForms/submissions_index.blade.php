@extends('admin.layouts.app')

@section('title', 'Application Forms')

@push('styles')
@endpush

@section('content')

    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Application Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ auth()->user()->hasRole('Super Admin') ? 'All Applications' : 'Workflow Applications' }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-auto">
                    {{-- Search Form can be handled by DataTable, but keeping if needed --}}
                </div>
                <div class="col-auto align-items-end ms-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-filter px-4">
                            <i class="bi bi-box-arrow-right me-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="mb-0">{{ $pageTitle ?? 'All Applications' }}</h3>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="applicationFormTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Applicant Name</th>
                                                            <th>Email</th>
                                                            <th>Registration ID</th>
                                                            <th>Forms</th>
                                                            <th>Status</th>
                                                            <th>Submitted Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($registration_data as $key => $app)
                                                            @php
                                                                $statusColors = [
                                                                    'Pending' => 'rgb(255, 193, 7)', // Warning (Yellow)
                                                                    'Approved' => 'rgb(25, 135, 84)', // Success (Green)
                                                                    'Rejected' => 'rgb(220, 53, 69)', // Danger (Red)
                                                                    'Returned' => 'rgb(220, 53, 69)', // Danger
                                                                    'Clerk' => 'rgb(108, 117, 125)', // Secondary
                                                                ];
                                                                $statusKey = $app['status'] ?? 'Pending';
                                                                // Fallback color
                                                                $bgColor = $statusColors[$statusKey] ?? 'rgb(108, 117, 125)';
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $app['user_name'] }}</td>
                                                                <td>{{ $app['user_email'] }}</td>
                                                                <td class="fw-semibold">{{ $app['application_no'] }}</td>
                                                                <td>{{ $app['application_name'] }}</td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-sm rounded-pill px-3 py-1 fw-bold"
                                                                        style="background-color: {{ $bgColor }}; color: #fff; border: none; border-radius: 8px;">
                                                                        {{ ucfirst($statusKey) }}
                                                                    </button>
                                                                </td>
                                                                <td>{{ $app['submitted_date'] }}</td>
                                                                <td>
                                                                    <a href="{{ $app['view_url'] }}" class="btn btn-sm"
                                                                        style="background-color:#055f0e; color:#fff;">
                                                                        <i class="bi bi-eye me-1"></i> View
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#applicationFormTable').DataTable({
                // Client-side processing (default)
                pageLength: 10,
                language: {
                    searchPlaceholder: "Search...",
                    search: "",
                },
                dom: '<"d-flex justify-content-between align-items-center mb-2"Bf>rt<"d-flex justify-content-between mt-3"lp>',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Print',
                        className: 'btn btn-secondary btn-sm text-white'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        className: 'btn btn-success btn-sm text-white'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="bi bi-file-earmark-text"></i> CSV',
                        className: 'btn btn-primary btn-sm text-white'
                    },
                ],
            });
        });
    </script>
@endpush