@extends('frontend.layouts2.master')

@section('title', 'User Dashboard')

@push('styles')
    <style>
        .dashboard-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            background: #fff;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .dashboard-icon {
            font-size: 40px;
            color: #0d6efd;
        }

        /* Certificate Card Styles */
        .certificate-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 16px !important;
        }

        .certificate-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 12px 32px rgba(255, 107, 53, 0.25) !important;
        }

        .certificate-item {
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .certificate-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .certificate-icon-pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }
        }

        .btn-orange {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, #f7931e 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
            color: white;
        }
    </style>
@endpush

@section('content')
    <main class="container py-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-1">Welcome, {{ Auth::user()->name ?? 'Guest' }}</h2>
                <p class="text-muted">Here's what's happening with your account.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Total Applications -->
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <div class="dashboard-icon mb-2 text-primary">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Total Applications</h5>
                    <p class="text-muted mb-0">{{ $totalApplications ?? 0 }}</p>
                </div>
            </div>

            <!-- Total Forms Applied -->
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <div class="dashboard-icon mb-2 text-info">
                        <i class="bi bi-folder-check"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Total Forms Applied</h5>
                    <p class="text-muted mb-0">{{ $totalUserApplications ?? 0 }}</p>
                </div>
            </div>

            <!-- Submitted -->
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <div class="dashboard-icon mb-2 text-primary">
                        <i class="bi bi-send-check"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Submitted</h5>
                    <p class="text-muted mb-0">{{ $statusCounts['submitted'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Approved -->
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <div class="dashboard-icon mb-2 text-success">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Approved</h5>
                    <p class="text-muted mb-0">{{ $statusCounts['approved'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Pending -->
            <div class="col-md-3 mt-2">
                <div class="dashboard-card text-center">
                    <div class="dashboard-icon mb-2 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Pending</h5>
                    <p class="text-muted mb-0">{{ $statusCounts['draft'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Rejected -->
            <div class="col-md-3 mt-2">
                <div class="dashboard-card text-center">
                    <div class="dashboard-icon mb-2 text-danger">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Rejected</h5>
                    <p class="text-muted mb-0">{{ $statusCounts['rejected'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- My Certificates Section -->
        @if($certificates->isNotEmpty())
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <!-- Orange Header -->
                        <div class="card-header border-0 py-3"
                            style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);">
                            <h5 class="mb-0 fw-semibold text-white"><i class="bi bi-award-fill me-2"></i>My Certificates</h5>
                        </div>

                        <!-- Card Body with Certificate Cards -->
                        <div class="card-body p-4">
                            <div class="row g-4">
                                @foreach($certificates as $cert)
                                    <div class="col-md-3">
                                        <div class="certificate-item text-center p-4 border rounded-3 h-100">
                                            <!-- Certificate Icon -->
                                            <div class="certificate-icon-pulse mb-3">
                                                <i class="bi bi-patch-check-fill text-success" style="font-size: 3.5rem;"></i>
                                            </div>

                                            <!-- Certificate Name -->
                                            <h6 class="fw-bold mb-2 text-dark">{{ $cert->type_name }}</h6>
                                            <p class="text-muted small mb-1">Registration ID</p>
                                            <p class="fw-semibold text-primary mb-3">{{ $cert->registration_id }}</p>

                                            <!-- Download Button -->
                                            <a href="{{ route('applications.certificate.download', ['type' => $cert->type, 'id' => $cert->id]) }}"
                                                class="btn btn-orange w-100" target="_blank">
                                                <i class="bi bi-download me-2"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent activity -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">Recent Activity</h5>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($recentActivities) && count($recentActivities))
                            <ul class="list-group list-group-flush">
                                @foreach($recentActivities as $activity)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $activity->description }}</span>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-4 text-center text-muted">
                                No recent activities yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Example: any dashboard-specific JS
        console.log("Dashboard loaded!");
    </script>
@endpush