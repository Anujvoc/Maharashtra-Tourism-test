<div class="card">
    <div class="card-header bg-dark text-white">
        <h6 class="mb-0 text-white">Workflow History</h6>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
            @forelse($application->workflowLogs->sortByDesc('created_at') as $log)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold small">{{ $log->user->name ?? 'System' }} ({{ $log->stage }})</span>
                        <span class="text-muted small"
                            style="font-size: 0.75rem;">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <div class="small mt-1">
                        <span class="fw-bold {{ $log->status == 'Approved' ? 'text-success' : 'text-danger' }}">
                            [{{ $log->status }}]
                        </span>

                        @if($log->siteVisitReport)
                            {{ $log->remark }}
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $log->siteVisitReport->file_path) }}" target="_blank"
                                    class="badge bg-info text-dark text-decoration-none">
                                    <i class="bi bi-eye me-1"></i> View Report
                                </a>
                                {{-- Taluka Report --}}
                                @if($log->siteVisitReport->taluka_file_path)
                                    <a href="{{ asset('storage/' . $log->siteVisitReport->taluka_file_path) }}" target="_blank"
                                        class="badge bg-success text-white text-decoration-none ms-2">
                                        <i class="bi bi-eye me-1"></i> View Taluka Report
                                    </a>
                                @endif
                            </div>
                        @elseif(\Illuminate\Support\Str::contains($log->remark, '(Report:'))
                            @php
                                $parts = explode('(Report:', $log->remark);
                                $cleanRemark = trim($parts[0]);
                                $path = trim(str_replace(')', '', $parts[1] ?? ''));
                             @endphp
                            {{ $cleanRemark }}
                            @if(!empty($path))
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                        class="badge bg-info text-dark text-decoration-none">
                                        <i class="bi bi-eye me-1"></i> View Report
                                    </a>
                                </div>
                            @endif
                        @else
                            {{ $log->remark }}
                        @endif
                    </div>
                    @if($log->is_public)
                        <span class="badge bg-light text-primary border border-primary mt-1" style="font-size: 0.65rem;">Visible
                            to User</span>
                    @endif
                </div>
            @empty
                <div class="list-group-item text-center text-muted">
                    No workflow history available.
                </div>
            @endforelse
        </div>
    </div>
</div>