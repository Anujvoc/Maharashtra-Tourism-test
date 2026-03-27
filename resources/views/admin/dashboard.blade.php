@extends('backend.layouts.app')

@section('title', 'Admin | Dashboard')
@section('page_heading', 'Dashboard')

@push('styles')
    <style>
        .dashboard-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            background: rgba(209, 205, 236, 1);
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
    </style>
@endpush

@section('content')

<main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">

        </div>
      </div>

      <div class="row g-3">
				<div class="card mt-2">

					<div class="card-body">

{{--
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
                        --}}
                        <div class="card-body">


                            <div class="row g-4">
                                <!-- Total Applications -->
                                <div class="col-md-3">
                                    <div class="dashboard-card text-center">
                                        <div class="dashboard-icon mb-2 text-primary">
                                            <i class="bi bi-clipboard-data"></i>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Total Applications</h5>
                                        <!-- <p class="text-muted mb-0">{{ $totalApplications ?? 0 }}</p> -->
                                         <p class="text-muted mb-0">{{ 790 }}</p>
                                    </div>
                                </div>

                                <!-- Total Forms Applied -->

                                {{-- -
                                <div class="col-md-3">
                                    <div class="dashboard-card text-center">
                                        <div class="dashboard-icon mb-2 text-info">
                                            <i class="bi bi-folder-check"></i>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Total Forms Applied</h5>
                                        <!-- <p class="text-muted mb-0">{{ $totalUserApplications ?? 0 }}</p> -->
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
                                        <!-- <p class="text-muted mb-0">{{ $statusCounts['submitted'] ?? 0 }}</p> -->
                                          <p class="text-muted mb-0">{{ $statusCounts['submitted'] ?? 0 }}</p>
                                    </div>
                                </div>
                                --}}

                                <!-- Approved -->
                                <div class="col-md-3">
                                    <div class="dashboard-card text-center">
                                        <div class="dashboard-icon mb-2 text-success">
                                            <i class="bi bi-patch-check-fill"></i>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Approved</h5>
                                        <!-- <p class="text-muted mb-0">{{ $statusCounts['approved'] ?? 0 }}</p> -->
                                             <p class="text-muted mb-0">{{ 505 }}</p>
                                    </div>
                                </div>

                                <!-- Pending -->
                                <div class="col-md-3 mt-4">
                                    <div class="dashboard-card text-center">
                                        <div class="dashboard-icon mb-2 text-warning">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Pending</h5>
                                        <!-- <p class="text-muted mb-0">{{ $statusCounts['draft'] ?? 0 }}</p> -->
                                        <p class="text-muted mb-0">{{ 185 }}</p>
                                    </div>
                                </div>

                                <!-- Rejected -->
                                <div class="col-md-3 mt-4">
                                    <div class="dashboard-card text-center">
                                        <div class="dashboard-icon mb-2 text-danger">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Rejected</h5>
                                        <!-- <p class="text-muted mb-0">{{ $statusCounts['rejected'] ?? 0 }}</p> -->
                                          <p class="text-muted mb-0">{{ 100 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>


					</div>
				</div>



    </div>


    <div class="card-body">

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-table me-2 text-primary"></i>Division Applications
                            Report</h5>
                    </div>
                    <div class="card-body">
                        <!-- Date Filter -->
                        <div class="row mb-4 align-items-end">
                            <div class="col-md-3">
                                <label for="min-date" class="form-label text-muted small text-uppercase fw-bold">From
                                    Date</label>
                                <input type="date" id="min-date" class="form-control" placeholder="From date">
                            </div>
                            <div class="col-md-3">
                                <label for="max-date" class="form-label text-muted small text-uppercase fw-bold">To
                                    Date</label>
                                <input type="date" id="max-date" class="form-control" placeholder="To date">
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-outline-secondary btn-sm"
                                    onclick="$('#min-date').val(''); $('#max-date').val(''); table.draw();"><i
                                        class="bi bi-arrow-counterclockwise"></i> Reset Filter</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="division-table" class="table table-bordered align-middle table-hover">

                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center text-uppercase" style="width: 20%;">Division</th>
                                        <th class="text-center text-uppercase" style="width: 10%;">Total Applied</th>
                                        <th class="text-center text-uppercase" style="width: 30%;">Application Status</th>
                                        <th class="text-center text-uppercase" style="width: 10%;">Action</th>
                                        <th class="text-center text-uppercase" style="width: 10%;">Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- Amravati Division -->
                                        <tr>
                                            <td class="fw-semibold">Amravati Division</td>
                                            <td class="text-center"><span class="badge bg-primary rounded-pill">110</span></td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Pending</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-warning text-dark rounded-pill">25</span>
                                                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Approved</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success rounded-pill">75</span>
                                                        <a href="#" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Rejected</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger rounded-pill">10</span>
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                                            </td>
                                            <td class="text-center">2026-01-20</td>
                                        </tr>

                                        <!-- Aurangabad Division -->
                                        <tr>
                                            <td class="fw-semibold">Aurangabad Division</td>
                                            <td class="text-center"><span class="badge bg-primary rounded-pill">135</span></td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Pending</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-warning text-dark rounded-pill">35</span>
                                                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Approved</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success rounded-pill">85</span>
                                                        <a href="#" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Rejected</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger rounded-pill">15</span>
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                                            </td>
                                            <td class="text-center">2026-01-21</td>
                                        </tr>

                                        <!-- Konkan Division -->
                                        <tr>
                                            <td class="fw-semibold">Konkan Division</td>
                                            <td class="text-center"><span class="badge bg-primary rounded-pill">90</span></td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Pending</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-warning text-dark rounded-pill">20</span>
                                                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Approved</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success rounded-pill">60</span>
                                                        <a href="#" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Rejected</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger rounded-pill">10</span>
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                                            </td>
                                            <td class="text-center">2026-01-23</td>
                                        </tr>

                                        <!-- Nagpur Division -->
                                        <tr>
                                            <td class="fw-semibold">Nagpur Division</td>
                                            <td class="text-center"><span class="badge bg-primary rounded-pill">140</span></td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Pending</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-warning text-dark rounded-pill">30</span>
                                                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Approved</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success rounded-pill">90</span>
                                                        <a href="#" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Rejected</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger rounded-pill">20</span>
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                                            </td>
                                            <td class="text-center">2026-01-22</td>
                                        </tr>

                                        <!-- Nashik Division -->
                                        <tr>
                                            <td class="fw-semibold">Nashik Division</td>
                                            <td class="text-center"><span class="badge bg-primary rounded-pill">115</span></td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Pending</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-warning text-dark rounded-pill">25</span>
                                                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Approved</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success rounded-pill">75</span>
                                                        <a href="#" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Rejected</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger rounded-pill">15</span>
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                                            </td>
                                            <td class="text-center">2026-01-24</td>
                                        </tr>

                                        <!-- Pune Division -->
                                        <tr>
                                            <td class="fw-semibold">Pune Division</td>
                                            <td class="text-center"><span class="badge bg-primary rounded-pill">200</span></td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Pending</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-warning text-dark rounded-pill">50</span>
                                                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Approved</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success rounded-pill">120</span>
                                                        <a href="#" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                                <div class="border-bottom my-1"></div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase fw-bold">Rejected</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger rounded-pill">30</span>
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                                            </td>
                                            <td class="text-center">2026-01-25</td>
                                        </tr>
                                    </tbody>

                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td>Total</td>
                                            <td class="text-center" id="total-applications">0</td>
                                            <td class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small text-uppercase">Pending</span>
                                                    <span class="badge bg-warning text-dark rounded-pill"
                                                        id="total-pending">0</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center my-1">
                                                    <span class="text-muted small text-uppercase">Approved</span>
                                                    <span class="badge bg-success rounded-pill" id="total-approved">0</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="text-muted small text-uppercase">Rejected</span>
                                                    <span class="badge bg-danger rounded-pill" id="total-rejected">0</span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
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
    // page specific JS
    console.log('Dashboard loaded');
  </script>
@endpush
