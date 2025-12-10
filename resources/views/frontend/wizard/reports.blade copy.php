<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Report - Maharashtra Tourism</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                opacity: 0.1;
                z-index: -1;
                pointer-events: none;
            }
            .watermark img {
                width: 400px;
                height: auto;
            }
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
        }

        .watermark img {
            width: 400px;
            height: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff6600;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .title {
            text-align: center;
            flex-grow: 1;
        }

        .title h1 {
            color: #ff6600;
            margin: 0;
            font-size: 24px;
        }

        .title h2 {
            margin: 5px 0 0;
            font-size: 18px;
            color: #333;
        }

        .qr-code {
            width: 100px;
            height: auto;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #ff6600;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .document-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .document-table th, .document-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .document-table th {
            background-color: #f5f5f5;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-submitted { background-color: #e7f4ff; color: #0066cc; }
        .status-approved { background-color: #e6f7ee; color: #0d6832; }
        .status-rejected { background-color: #fde8e8; color: #c53030; }
        .status-draft { background-color: #fef5e7; color: #c05621; }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .actions {
            margin: 20px 0;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #055f0e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 0 5px;
        }

        .btn:hover {
            background-color: #04450b;
        }

        .btn-print {
            background-color: #ff6600;
        }

        .btn-print:hover {
            background-color: #e55a00;
        }
    </style>
</head>
<body>
    @if($watermark)
    <div class="watermark">
        <img src="{{ $watermark }}" alt="Maharashtra Tourism Watermark">
    </div>
    @endif

    <div class="header">
        @if($logo)
        <div class="logo-container">
            <img src="{{ $logo }}" alt="Maharashtra Tourism Logo" class="logo">
        </div>
        @endif

        <div class="title">
            <h1>MAHARASHTRA TOURISM</h1>
            <h2>Application Report</h2>
        </div>

        <div class="qr-container">
            {{-- <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code"> --}}
        </div>
    </div>

    <div class="actions no-print">
        <a href="javascript:window.print()" class="btn btn-print">
            <i class="bi bi-printer"></i> Print Report
        </a>
        <a href="{{ route('applications.report.download', ['id' => $application->id, 'download' => true]) }}" class="btn">
            <i class="bi bi-download"></i> Download PDF
        </a>
        <a href="{{ route('applications.index') }}" class="btn" style="background-color: #6c757d;">
            <i class="bi bi-arrow-left"></i> Back to Applications
        </a>
    </div>

    <div class="section">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Application ID:</span>
                <div class="info-value">{{ $application->registration_id ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <div class="info-value">
                    <span class="status-badge status-{{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <span class="info-label">Submission Date:</span>
                <div class="info-value">{{ $application->submitted_at?->format('d M, Y') ?? 'Not submitted' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Progress:</span>
                <div class="info-value">
                    {{ $application->progress['done'] ?? 0 }} of {{ $application->progress['total'] ?? 7 }} steps completed
                </div>
            </div>
        </div>
    </div>

    @if($application->applicant)
    <div class="section">
        <div class="section-title">A) Applicant Details</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Name:</span>
                <div class="info-value">{{ $application->applicant->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Phone:</span>
                <div class="info-value">{{ $application->applicant->phone ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <div class="info-value">{{ $application->applicant->email ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Business Name:</span>
                <div class="info-value">{{ $application->applicant->business_name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">PAN:</span>
                <div class="info-value">{{ $application->applicant->pan ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Aadhaar:</span>
                <div class="info-value">{{ $application->applicant->aadhaar ?? '—' }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($application->property)
    <div class="section">
        <div class="section-title">B) Property Details</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Property Name:</span>
                <div class="info-value">{{ $application->property->property_name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Address:</span>
                <div class="info-value">{{ $application->property->address ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Total Area:</span>
                <div class="info-value">{{ $application->property->total_area_sqft ?? '—' }} sq.ft</div>
            </div>
            <div class="info-item">
                <span class="info-label">Operational:</span>
                <div class="info-value">
                    {{ isset($application->property->is_operational) ? ($application->property->is_operational ? 'Yes' : 'No') : '—' }}
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($application->accommodation)
    <div class="section">
        <div class="section-title">C) Accommodation Details</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Total Flats/Rooms:</span>
                <div class="info-value">{{ $application->accommodation->flats_count ?? '—' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Attached Toilet:</span>
                <div class="info-value">
                    {{ isset($application->accommodation->attached_toilet) ? ($application->accommodation->attached_toilet ? 'Yes' : 'No') : '—' }}
                </div>
            </div>
            <div class="info-item">
                <span class="info-label">Road Access:</span>
                <div class="info-value">
                    {{ isset($application->accommodation->road_access) ? ($application->accommodation->road_access ? 'Yes' : 'No') : '—' }}
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($application->documents && $application->documents->count() > 0)
    <div class="section">
        <div class="section-title">D) Submitted Documents</div>
        <table class="document-table">
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>File Name</th>
                    <th>Upload Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->documents as $document)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $document->category)) }}</td>
                    <td>{{ $document->original_name }}</td>
                    <td>{{ $document->created_at->format('d M, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('d M, Y \\a\\t h:i A') }}</p>
        <p>Maharashtra Tourism Development Corporation</p>
    </div>
</body>
</html>
