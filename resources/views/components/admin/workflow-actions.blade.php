@props(['application', 'type'])

<div class="bg-white1 p-6 rounded-lg shadow-md mt-6">
    <h3 class="text-lg font-semibold mb-4">Workflow Actions (Current Stage: {{ $application->current_stage }})</h3>

    <div class="space-y-4">
        <!-- Status Indicator -->
        <div class="flex items-center space-x-2">
            <span class="font-medium">Status:</span>
            <span
                class="px-3 py-1 rounded-full text-sm
                {{ $application->workflow_status == 'Approved' ? 'bg-green-100 text-green-800' :
    ($application->workflow_status == 'Rejected' ? 'bg-red-100 text-red-800' :
        ($application->workflow_status == 'Certificate Generated' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                {{ $application->workflow_status }}
            </span>
        </div>

        @if($application->workflow_status === 'Certificate Generated')

            {{-- Certificate Generated View --}}
            <div class="bg-light border border-success p-4 rounded text-center">
                <i class="bi bi-award-fill text-success" style="font-size: 3rem;"></i>
                <h4 class="text-success mt-2">Certificate Generated</h4>
                <p class="text-muted">The application has been fully approved and the certificate is ready.</p>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <a href="{{ route('admin.certificate.show', ['type' => $type, 'id' => $application->id]) }}"
                        target="_blank" class="btn btn-success">
                        <i class="bi bi-eye"></i> Preview Certificate
                    </a>
                    <a href="{{ route('admin.certificate.download', ['type' => $type, 'id' => $application->id]) }}"
                        class="btn btn-outline-success">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                </div>
            </div>

        @elseif(auth()->user()->hasRole($application->current_stage))

            <div class="d-flex flex-column gap-3">
                <!-- Approve Action -->
                <form action="{{ route('admin.workflow.approve', ['type' => $type, 'id' => $application->id]) }}"
                    method="POST" class="border p-3 rounded bg-light">
                    @csrf
                    <h5 class="mb-2 text-success">Approve & Forward</h5>
                    <textarea name="remark" rows="3" class="form-control mb-2 w-100"
                        placeholder="Optional remark..."></textarea>
                    <button type="submit" class="btn btn-success w-100">Approve</button>
                </form>

                <!-- Return Internal Action (Admin to Admin) -->
                @if($application->current_stage !== 'Clerk')
                    <form action="{{ route('admin.workflow.return', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" class="border p-3 rounded bg-light">
                        @csrf
                        <h5 class="mb-2 text-warning">Return to Previous Stage</h5>
                        <textarea name="remark" rows="3" class="form-control mb-2 w-100" placeholder="Reason for return..."
                            required></textarea>
                        <button type="submit" class="btn btn-warning text-white w-100">Return to
                            Previous</button>
                    </form>
                @endif

                <!-- Return/Clarify to User Action -->
                <form action="{{ route('admin.workflow.clarify', ['type' => $type, 'id' => $application->id]) }}"
                    method="POST" class="border p-3 rounded bg-light">
                    @csrf
                    <h5 class="mb-2 text-primary">Request Clarification (To User)</h5>
                    <textarea name="remark" rows="3" class="form-control mb-2 w-100" placeholder="Instructions to user..."
                        required></textarea>
                    <button type="submit" class="btn btn-primary w-100">Send to
                        User</button>
                </form>

                <!-- Site Visit Report (Only Dy Director) -->
                @if($application->current_stage === 'Dy Director')
                    <form action="{{ route('admin.workflow.site-report', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" enctype="multipart/form-data" class="border p-3 rounded bg-light">
                        @csrf
                        <h5 class="mb-2 text-primary">Submit Site Visit Report</h5>
                        <input type="file" name="report_file" class="form-control mb-2" required>
                        <textarea name="remark" rows="3" class="form-control mb-2 w-100" placeholder="Report summary..."
                            required></textarea>
                        <button type="submit" class="btn btn-primary w-100">Upload &
                            Forward</button>
                    </form>
                @endif
            </div>

        @else
            <div class="bg-gray-100 p-4 rounded text-gray-600 italic">
                You do not have permission to take action at this stage ({{ $application->current_stage }}).
            </div>
        @endif

        <!-- History / Remarks Log -->
        <div class="mt-8">
            <h4 class="font-semibold mb-3">Workflow History</h4>
            <div class="bg-gray-50 rounded border divide-y">
                @foreach($application->workflowLogs->sortByDesc('created_at') as $log)
                    <div class="p-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-sm">{{ $log->user->name ?? 'System' }}
                                ({{ $log->stage }})</span>
                            <span class="text-xs text-gray-500">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="text-sm mt-1">
                            <span
                                class="font-medium {{ $log->status == 'Approved' ? 'text-green-600' : 'text-red-600' }}">[{{ $log->status }}]</span>
                            {{ $log->remark }}
                        </div>
                        @if($log->is_public)
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded ml-2">Visible to User</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>