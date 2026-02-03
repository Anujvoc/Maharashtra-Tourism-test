<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligibility Registration Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --brand: #ff6600;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.95rem;
        }

        .review-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 2rem;
            background: #fff;
        }

        .review-card-header {
            background: var(--brand);
            color: #fff;
            padding: 1rem 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .review-card-body {
            padding: 1.5rem;
        }

        .preview-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .preview-value {
            color: #212529;
            font-weight: 500;
            word-break: break-word;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .review-card {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="text-center mb-5 border-bottom pb-4">
            <h2 class="fw-bold text-dark">Application Details</h2>
            <div class="d-flex justify-content-center gap-2 mt-2">
                <span class="badge bg-secondary">Eligibility Registration</span>
                <span class="badge {{ $application->status == 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                    Status: {{ ucwords($application->status) }}
                </span>
            </div>
        </div>

        {{-- General Info --}}
        <div class="review-card">
            <div class="review-card-header"><i class="bi bi-info-circle"></i> Basic Details</div>
            <div class="review-card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="preview-label">Applicant Name</div>
                        <div class="preview-value">{{ $application->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="preview-label">Entity Name</div>
                        <div class="preview-value">{{ $application->entity_name ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="preview-label">Location</div>
                        <div class="preview-value">{{ $application->location ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="preview-label">Constitution</div>
                        <div class="preview-value">{{ $application->constitution ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enclosures --}}
        <div class="review-card">
            <div class="review-card-header"><i class="bi bi-folder"></i> Documents (Enclosures)</div>
            <div class="review-card-body">
                @php
                    $enclosures = $application->enclosures ?? [];
                    $labels = [
                        'travel_life' => 'Travel for LiFE Certificate',
                        'ca_certificate' => 'CA Certificate',
                        'project_report' => 'Project Report',
                        'shop_licence' => 'Shop Licence / FDA',
                        'star_classification' => 'Star Classification',
                        'mpcb_noc' => 'MPCB NOC',
                        'balance_sheets' => 'Balance Sheets',
                        'proof_commercial' => 'Proof of Commercial Ops',
                        'declaration_commencement' => 'Declaration of Commencement',
                        'completion_certificate' => 'Completion Certificate',
                        'gst_registration' => 'GST Registration',
                        'processing_fee' => 'Processing Fee Challan',
                    ];
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40%">Document Name</th>
                                <th>Doc No.</th>
                                <th>Issue Date</th>
                                <th>Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($labels as $key => $label)
                                @php $doc = $enclosures[$key] ?? null; @endphp
                                @if($doc && !empty($doc['file_path']))
                                    <tr>
                                        <td class="fw-bold">{{ $label }}</td>
                                        <td>{{ $doc['doc_no'] ?? '-' }}</td>
                                        <td>{{ $doc['issue_date'] ?? '-' }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $doc['file_path']) }}" target="_blank"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>