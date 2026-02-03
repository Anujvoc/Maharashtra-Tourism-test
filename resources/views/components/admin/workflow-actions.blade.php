@props(['application', 'type'])

<div class="bg-white1 p-6 rounded-lg shadow-md mt-6">
    <h3 class="text-lg font-semibold mb-4">Workflow Actions (Current Stage: {{ $application->current_stage }})</h3>

    <div class="space-y-4">
        <!-- Status Indicator -->
        <div class="flex items-center space-x-2">
            <span class="font-medium">Status:</span>
            <span
                class="px-3 py-1 rounded-full text-sm
                {{ ($application->workflow_status == 'Approved' || $application->workflow_status == 'Certificate Issued') ? 'bg-green-100 text-green-800' :
    ($application->workflow_status == 'Rejected' ? 'bg-red-100 text-red-800' :
        ($application->workflow_status == 'Certificate Generated' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                {{ $application->workflow_status }}
            </span>
        </div>

        @if($application->workflow_status === 'Certificate Issued')

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

        @elseif($application->workflow_status === 'Certificate Pending' && auth()->user()->hasRole('Asst Director'))

            {{-- Certificate Upload for Asst Director --}}
             <form action="{{ route('admin.workflow.upload-certificate', ['type' => $type, 'id' => $application->id]) }}"
                method="POST" enctype="multipart/form-data" class="border p-3 rounded bg-light border-primary">
                @csrf
                <div class="text-center mb-3">
                    <i class="bi bi-file-earmark-arrow-up text-primary" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-2 text-primary">Upload Final Certificate</h5>
                    <p class="text-muted small">Director has approved. Please upload the specific certificate.</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Certificate PDF <span class="text-danger">*</span></label>
                    <input type="file" name="certificate_file" class="form-control" accept="application/pdf" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-upload"></i> Upload & Issue Certificate
                </button>
            </form>

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

                <!-- Reject Application (Asst Director, Dy Director, Joint Director, Director) -->
                {{-- Clerk cannot reject the full form --}}
                @if(in_array($application->current_stage, ['Asst Director', 'Dy Director', 'Joint Director', 'Director']))
                    <button type="button" class="btn btn-danger w-100 mb-0" data-bs-toggle="modal" data-bs-target="#rejectAppModal">
                        Reject Application (Full)
                    </button>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectAppModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.workflow.reject', ['type' => $type, 'id' => $application->id]) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Reject Application Permanently</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger fw-bold">Warning: This will mark the application as Rejected and stop the workflow.</p>
                                        <div class="mb-3">
                                            <label class="form-label">Rejection Remark <span class="text-danger">*</span></label>
                                            <textarea name="remark" class="form-control" rows="3" required placeholder="Reason for rejection..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Return Internal Action (Admin to Admin) -->
                @php
                    $returnLabel = "Return to Previous Stage";
                    $showInternalReturn = false;
                    // Logic: Joint/Dy -> Asst, Asst -> Clerk
                    if($application->current_stage === 'Joint Director') {
                        $returnLabel = "Return to Asst Director";
                        $showInternalReturn = true;
                    } elseif ($application->current_stage === 'Dy Director') {
                        $returnLabel = "Return to Asst Director";
                        $showInternalReturn = true;
                    } elseif ($application->current_stage === 'Asst Director') {
                        $returnLabel = "Return to Clerk (Clarification)";
                        $showInternalReturn = true;
                    }
                @endphp

                @if($showInternalReturn)
                    <form action="{{ route('admin.workflow.return', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" class="border p-3 rounded bg-light">
                        @csrf
                        <h5 class="mb-2 text-warning">{{ $returnLabel }}</h5>
                        <p class="text-muted small">This will send the application back for clarification/correction.</p>
                        <textarea name="remark" rows="3" class="form-control mb-2 w-100" placeholder="Reason for return/correction..."
                            required></textarea>
                        <button type="submit" class="btn btn-warning text-white w-100">
                            {{ $returnLabel }}
                        </button>
                    </form>
                @endif

                <!-- Return/Clarify to User Action (CLERK ONLY) -->
                @if(auth()->user()->hasRole('Clerk'))
                    <form action="{{ route('admin.workflow.clarify', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" class="border p-3 rounded bg-light">
                        @csrf
                        <h5 class="mb-2 text-primary">Request Clarification (To User)</h5>
                        <p class="text-muted small">Send back to user for document re-upload or correction.</p>
                        <textarea name="remark" rows="3" class="form-control mb-2 w-100" placeholder="Instructions to user..."
                            required></textarea>
                        <button type="submit" class="btn btn-primary w-100">Send to User</button>
                    </form>
                @endif
                
                <!-- Site Visit Request (Only Dy Director) - If report NOT present -->
                 @if($application->current_stage === 'Dy Director' && !$hasSiteReport && $application->workflow_status !== 'Site Visit Requested')
                    <form action="{{ route('admin.workflow.request-visit', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" class="border p-3 rounded bg-light border-info mb-3">
                        @csrf
                        <h5 class="mb-2 text-info">Request Site Visit Report</h5>
                        <p class="text-muted small">Notify Clerk to upload the Site Visit Report.</p>
                        <button type="submit" class="btn btn-info text-white w-100">
                            Notify Clerk
                        </button>
                    </form>
                @endif

                <!-- Site Visit Report Upload (Clerk - when Requested) -->
                 @if($application->workflow_status === 'Site Visit Requested' && auth()->user()->hasRole('Clerk'))
                    <form action="{{ route('admin.workflow.site-report', ['type' => $type, 'id' => $application->id]) }}"
                        method="POST" enctype="multipart/form-data" class="border p-3 rounded bg-light mb-3">
                        @csrf
                        <h5 class="mb-2 text-primary">Upload Site Visit Report (Requested)</h5>
                        <div class="mb-3">
                            <label class="form-label text-primary fw-bold">Site Visit Report <span class="text-danger">*</span></label>
                            <input type="file" name="site_visit_report" class="form-control" accept="application/pdf" required>
                        </div>
                         {{-- Taluka Report for Agriculture --}}
                        @if($application instanceof \App\Models\frontend\ApplicationForm\AgricultureRegistration)
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Taluka Agri Officer Inspection Report <span class="text-danger">*</span></label>
                                <input type="file" name="taluka_report_file" class="form-control" accept="application/pdf" required>
                            </div>
                        @endif
                        <div class="mb-2">
                            <label class="form-label text-primary fw-bold">Remark <span class="text-danger">*</span></label>
                             <textarea name="remark" rows="2" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Report to Dy Director</button>
                    </form>
                @endif

                <!-- Site Visit Report Submit (Dy Director logic - usually hidden now as they Request it, but user prompt said "Dy Director controls/rejects it") -->
                <!-- Assuming Dy Director approves/rejects via the document verification or main approval flow once uploaded. -->

                <!-- Helper for Dy Director to see uploaded report logic is in document-verification or global report view. -->
                
                <!-- Old Site Visit Logic (Preserved if needed, but modified condition) -->
                @if($application->current_stage === 'Dy Director' && $hasSiteReport)
                    <!-- If report exists, Dy Director sees Approve/Return buttons above. -->
                @endif

                @if(false) <!-- Disable old block to avoid confusion, using new request flow -->
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