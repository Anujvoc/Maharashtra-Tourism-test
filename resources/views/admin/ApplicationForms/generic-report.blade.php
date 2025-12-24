<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Report</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --brand: #ff6600;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.95rem;
        }

        /* Card Styling */
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
            font-size: 1.1rem;
        }

        .review-card-body {
            padding: 1.5rem;
        }

        /* Data Items */
        .preview-item {
            margin-bottom: 1rem;
            border-bottom: 1px dashed #eee;
            padding-bottom: 0.5rem;
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .preview-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .preview-value {
            color: #212529;
            font-weight: 500;
            font-size: 1rem;
            word-break: break-word;
        }

        /* Document Previews */
        .doc-preview-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            transition: all 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fdfdfd;
        }

        .doc-preview-card:hover {
            border-color: var(--brand);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .doc-thumb {
            max-height: 120px;
            max-width: 100%;
            object-fit: contain;
            border: 1px solid #eee;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .doc-icon {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 10px;
        }

        /* Layout */
        .header-section {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .header-section h1 {
            color: #333;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .badge-status {
            font-size: 1rem;
            padding: 0.5em 1em;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .review-card {
                box-shadow: none;
                border: 1px solid #ccc;
            }

            body {
                background: #fff;
            }
        }
    </style>
</head>

<body>

    <div class="container py-5">

        {{-- Header --}}
        <div class="header-section">
            <h1>Application Details</h1>
            <div class="d-flex justify-content-center gap-3 align-items-center">
                <span class="badge bg-secondary">{{ ucwords(str_replace('-', ' ', $type)) }}</span>
                <span
                    class="badge {{ $application->status == 'approved' ? 'bg-success' : ($application->status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} badge-status">
                    Status: {{ ucwords($application->status) }}
                </span>
            </div>
            <div class="mt-2 text-muted">
                Submitted on:
                {{ $application->submitted_at ? \Carbon\Carbon::parse($application->submitted_at)->format('d M Y, h:i A') : 'N/A' }}
            </div>
        </div>

        @php
            // Segregate Attributes
            $attributes = $application->getAttributes();
            // dd($attributes);
            $skip = ['id', 'user_id', 'slug_id', 'created_at', 'updated_at', 'deleted_at', 'application_form_id', 'current_step', 'progress', 'is_apply', 'is_completed', 'submitted_at', 'status', 'registration_id'];

            $infoFields = [];
            $docFields = [];
            $longFields = [];

            foreach ($attributes as $key => $value) {
                if (in_array($key, $skip))
                    continue;
                if (is_array($value) || is_object($value)) {
                    $longFields[$key] = $value; // JSON data usually
                    continue;
                }

                // Heuristic for documents
                if (Str::contains($key, ['path', 'file', 'image', 'photo', 'signature', 'doc_'])) {
                    if (!empty($value) && is_string($value)) {
                        $docFields[$key] = $value;
                    }
                }
                // Description texts
                elseif (strlen($value) > 100 || Str::contains($key, ['description', 'address', 'remark'])) {
                    $infoFields[$key] = $value; // Treat as info but maybe wide
                }
                // Standard Info
                else {
                    $infoFields[$key] = $value;
                }
            }
        @endphp

        {{-- 1. General Information Card --}}
        <div class="review-card">
            <div class="review-card-header">
                <i class="bi bi-info-circle-fill"></i> Application Information
            </div>
            <div class="review-card-body">
                <div class="row">
                    @foreach($infoFields as $key => $value)
                        <div class="col-md-6 col-lg-4">
                            <div class="preview-item">
                                <div class="preview-label">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                <div class="preview-value">
                                    @if($value === true || $value === 1) <span class="badge bg-success">Yes</span>
                                    @elseif($value === false || $value === 0) <span class="badge bg-secondary">No</span>
                                    @else {{ $value ?: '—' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Complex/Long Data --}}
                @if(count($longFields) > 0)
                    <hr class="my-4">
                    <h6 class="text-muted mb-3 text-uppercase fw-bold">Additional Details</h6>
                    @foreach($longFields as $key => $value)
                        <div class="mb-4">
                            <div class="preview-label mb-2">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                            <div class="bg-light p-3 rounded small">
                                @if(is_array($value) && (in_array($key, ['enclosures', 'other_documents', 'other_docs', 'entrepreneurs', 'entrepreneurs_profile', 'means_of_finance', 'investment_components', 'cost_component']) || isset($value[0]) && is_array($value[0])))
                                    {{-- Render as Table --}}
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0 bg-white">
                                            <thead>
                                                @if(isset($value[0]) && is_array($value[0]))
                                                    <tr>
                                                        @foreach(array_keys($value[0]) as $header)
                                                             <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                                                        @endforeach
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <th>Key</th>
                                                        <th>Value</th>
                                                    </tr>
                                                @endif
                                            </thead>
                                            <tbody>
                                                @if(isset($value[0]) && is_array($value[0]))
                                                    {{-- Array of objects --}}
                                                    @foreach($value as $row)
                                                        <tr>
                                                            @foreach($row as $cellKey => $cellValue)
                                                                <td>
                                                                    @if(is_string($cellValue) && Str::contains($cellKey, ['path', 'file']))
                                                                        <a href="{{ asset('storage/'.$cellValue) }}" target="_blank" class="btn btn-xs btn-link p-0">View File</a>
                                                                    @else
                                                                        {{ is_array($cellValue) ? json_encode($cellValue) : $cellValue }}
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @else
                                                     {{-- Associative Array --}}
                                                     @foreach($value as $subKey => $subValue)
                                                        <tr>
                                                            <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $subKey)) }}</td>
                                                            <td>
                                                                @if(is_array($subValue))
                                                                    {{-- Nested Array (e.g. Enclosures with file_path) --}}
                                                                     @foreach($subValue as $nKey => $nVal)
                                                                        <div><strong>{{ ucwords(str_replace('_', ' ', $nKey)) }}:</strong>
                                                                             @if(Str::contains($nKey, ['path', 'file']) && is_string($nVal))
                                                                                <a href="{{ asset('storage/'.$nVal) }}" target="_blank" class="text-primary">View</a>
                                                                             @else
                                                                                {{ is_array($nVal) ? json_encode($nVal) : $nVal }}
                                                                             @endif
                                                                        </div>
                                                                     @endforeach
                                                                @else
                                                                    {{ $subValue }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                     @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif(is_string($value))
                                    {{ $value }}
                                @else
                                    <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- 2. Documents Card --}}
        @if(count($docFields) > 0)
            <div class="review-card">
                <div class="review-card-header">
                    <i class="bi bi-folder-fill"></i> Uploaded Documents
                </div>
                <div class="review-card-body">
                    <div class="row g-4">
                        @foreach($docFields as $key => $path)
                            @php
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $url = asset('storage/' . $path);
                                $label = ucwords(str_replace(['_', 'path', 'file'], ' ', $key));
                            @endphp
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="doc-preview-card">
                                    @if($isImage)
                                        <img src="{{ $url }}" alt="{{ $label }}" class="doc-thumb"
                                            onclick="showImage('{{ $url }}')">
                                    @elseif($ext === 'pdf')
                                        <i class="bi bi-file-earmark-pdf doc-icon"></i>
                                    @else
                                        <i class="bi bi-file-earmark-text doc-icon text-secondary"></i>
                                    @endif

                                    <div class="fw-bold text-center small mb-2">{{ $label }}</div>

                                    @if($isImage)
                                        <button class="btn btn-sm btn-outline-primary w-100" onclick="showImage('{{ $url }}')">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    @else
                                        <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="text-center no-print pb-5">
            <button onclick="window.print()" class="btn btn-dark btn-lg">
                <i class="bi bi-printer"></i> Print Report
            </button>
        </div>

    </div>

    {{-- Image Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center bg-light">
                    <img id="modalImage" src="" class="img-fluid" style="max-height: 80vh;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImage(src) {
            document.getElementById('modalImage').src = src;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>

</body>

</html>
