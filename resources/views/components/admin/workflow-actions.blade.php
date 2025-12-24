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
                @php
                    $currentRole = auth()->user()->getRoleNames()->first();
                    $pendingDocs = $application->verificationDocuments->filter(function ($doc) use ($currentRole) {
                        $approvals = $doc->role_approvals ?? [];
                        // Check if explicity Pending or Re-uploaded
                        $status = $approvals[$currentRole]['status'] ?? 'Pending';
                        $isReuploaded = isset($approvals['_meta']['is_reuploaded']) && $approvals['_meta']['is_reuploaded'];
                        return $status === 'Pending' || $isReuploaded;
                    })->count();
                @endphp

                <!-- Approve Action (Standard) -->
                <form id="approve-form"
                    action="{{ route('admin.workflow.approve', ['type' => $type, 'id' => $application->id]) }}"
                    method="POST" class="border p-3 rounded bg-light">
                    @csrf
                    <h5 class="mb-2 text-success">Approve & Forward</h5>
                    <textarea id="main-remark" name="remark" rows="3" class="form-control mb-2 w-100"
                        placeholder="Confirm all documents are verified..." required></textarea>
                    <button type="button" onclick="validateMainApproval()" class="btn btn-success w-100">Approve &
                        Forward</button>
                </form>

                @php
                    $hasSiteReport = $application->workflowLogs->where('status', 'Site Visit Report')->count() > 0;
                @endphp
                <script>
                    function validateMainApproval() {
                        // Backend State passed to JS
                        const currentStage = "{{ $application->current_stage }}";
                        const hasSiteReport = @json(\App\Models\SiteVisitReport::where('application_id', $application->id)->where('application_type', get_class($application))->exists());
                        const isAgriculture = @json($application instanceof \App\Models\frontend\ApplicationForm\AgricultureRegistration);
                        const hasTalukaReport = @json(\App\Models\SiteVisitReport::where('application_id', $application->id)
                            ->where('application_type', get_class($application))
                            ->whereNotNull('taluka_file_path')
                        ->exists());

                        // Scope to the verification table to avoid picking up other elements
                        const table = document.getElementById('verification-table');
                        // If table not found (e.g. view mode), assume no documents or handled server side, but usually it's there.
                        // However, we should be safe.

                        let pendingButtons = 0;
                        let rejectedDocs = 0;

                        if (table) {
                            pendingButtons = table.querySelectorAll('.btn-approve').length;
                            rejectedDocs = table.querySelectorAll('.bg-danger').length; // Rejected badges
                        }

                        const remark = document.getElementById('main-remark').value.trim();

                        if (pendingButtons > 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pending Documents',
                                text: 'There are ' + pendingButtons + ' documents pending approval. All documents must be approved before forwarding.',
                                confirmButtonColor: '#ffc107'
                            });
                            return;
                        }

                        if (rejectedDocs > 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Rejected Documents',
                                text: 'There are ' + rejectedDocs + ' rejected documents. You cannot approve the application if any document is rejected. Please use the "Return to Previous Stage" option instead.',
                                confirmButtonColor: '#d33'
                            });
                            return;
                        }

                        // Dy Director Specific Check: Data Site Visit Report
                        if (currentStage === 'Dy Director') {
                            if (!hasSiteReport) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Site Visit Report Required',
                                    text: 'please uploade site-reprt',
                                    confirmButtonColor: '#d33'
                                });
                                return;
                            }

                            if (isAgriculture && !hasTalukaReport) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Taluka Report Required',
                                    text: 'please uploade Taluka Agri Officer Inspection Report',
                                    confirmButtonColor: '#d33'
                                });
                                return;
                            }
                        }

                        if (!remark) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Remark Required',
                                text: 'Please provide a final remark before forwarding.',
                                confirmButtonColor: '#ffc107'
                            });
                            return;
                        }

                        // Confirmation
                        Swal.fire({
                            title: 'Approve & Forward?',
                            text: "Are you sure you want to forward this application to the next stage?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Forward it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('approve-form').submit();
                            }
                        });
                    }
                </script>

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
                <!-- Site Visit Report (Only Dy Director) -->
                @if($application->current_stage === 'Dy Director')
                    <form action="{{ route('admin.workflow.site-report', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" enctype="multipart/form-data" class="border p-3 rounded bg-light mb-3">
                        @csrf
                        <h5 class="mb-2 text-primary">Submit Site Visit Report</h5>

                        <div class="mb-3">
                            <label class="form-label text-primary fw-bold">Site Visit Report (PDF only, max 10MB) <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="site_visit_report" id="site_visit_report" class="form-control"
                                accept="application/pdf" required onchange="previewPdf(this, 'preview-site-report')">
                            <div id="preview-site-report" class="mt-2"></div>
                        </div>

                        {{-- Taluka Report for Agriculture --}}
                        @if($application instanceof \App\Models\frontend\ApplicationForm\AgricultureRegistration)
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Taluka Agri Officer Inspection Report (PDF only, max
                                    10MB) <span class="text-danger">*</span></label>
                                <input type="file" name="taluka_report_file" id="taluka_report_file" class="form-control"
                                    accept="application/pdf" required onchange="previewPdf(this, 'preview-taluka-report')">
                                <div id="preview-taluka-report" class="mt-2"></div>
                            </div>
                        @endif

                        <div class="mb-2">
                            <label class="form-label text-primary fw-bold">Remark <span class="text-danger">*</span></label>
                            <textarea name="remark" rows="2" class="form-control" placeholder="Enter remarks..."
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-upload"></i> Upload Report
                        </button>
                    </form>

                    <script>
                        function previewPdf(input, previewId) {
                            const file = input.files[0];
                            const container = document.getElementById(previewId);
                            if (file && file.type === 'application/pdf') {
                                const fileURL = URL.createObjectURL(file);
                                container.innerHTML = `
                                            <a href="${fileURL}" target="_blank" class="badge bg-info text-dark text-decoration-none">
                                                <i class="bi bi-eye me-1"></i> Preview Selected PDF
                                            </a>`;
                            } else {
                                container.innerHTML = '';
                            }
                        }
                    </script>
                @endif
            </div>

        @else
            <div class="bg-gray-100 p-4 rounded text-gray-600 italic">
                You do not have permission to take action at this stage ({{ $application->current_stage }}).
            </div>
        @endif

        <!-- History / Remarks Log -->

    </div>
</div>