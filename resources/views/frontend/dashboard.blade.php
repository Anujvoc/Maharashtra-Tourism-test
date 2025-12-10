@extends('frontend.layouts2.master')

@section('title', 'User Dashboard')

@push('styles')
<style>
.dashboard-card {
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    background: #fff;
    transition: all 0.3s ease;
}
.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}
.dashboard-icon {
    font-size: 40px;
    color: #0d6efd;
}
</style>
@endpush

@section('content')
<main class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-1">Welcome, {{ Auth::user()->name ?? 'Guest' }}</h2>
            <p class="text-muted">Here’s what’s happening with your account.</p>
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
