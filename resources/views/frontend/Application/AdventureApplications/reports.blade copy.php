<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 10pt;
                background: white !important;
                color: black !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            table {
                page-break-inside: avoid;
                font-size: 9pt;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background-color: transparent !important;
            }

            .section-title {
                background-color: #d9edf7 !important;
                color: #000 !important;
                border: 1px solid #000 !important;
            }

            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                opacity: 0.1;
                font-size: 80pt;
                color: #000;
                z-index: -1;
                pointer-events: none;
            }
        }

        body {
            font-family: Verdana, Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            font-size: 12px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.05;
            font-size: 80pt;
            color: #000;
            z-index: 0;
            pointer-events: none;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
        }

        .header-table td {
            vertical-align: middle;
            padding: 10px 0;
        }

        .logo {
            max-width: 100px;
            height: auto;
        }

        .form-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            text-transform: uppercase;
            margin: 5px 0;
            color: #2c3e50;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
            position: relative;
            z-index: 1;
        }

        .info-table td, .info-table th {
            border: 1px solid #ddd;
            padding: 5px;
        }

        .info-table th {
            background-color: #f2f2f2;
            width: 20%;
            font-weight: bold;
        }

        .section-title {
            background-color: #2c3e50;
            color: white;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: 10px 0 6px 0;
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px;
            margin: 10px 0;
            position: relative;
            z-index: 1;
        }

        .document-card {
            border: 1px solid #ddd;
            text-align: center;
            padding: 6px;
        }

        .document-image {
            max-width: 100%;
            height: 120px;
            object-fit: cover;
            margin-bottom: 4px;
        }

        .qrcode {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .print-buttons {
            text-align: center;
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 6px;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-primary {
            background-color: #cce5ff;
            color: #004085;
        }


    </style>
</head>
<body>

    @php


    @endphp

    <div class="container">
        <div class="watermark">RTS</div>


        <!-- Header with Logo and QR Code in a table -->

        <table class="header-table">
            <tr>
                <td width="20%">
                    <img src="{{ asset('mah-logo-300x277.png') }}" alt="Logo" class="logo">
                </td>
                <td width="60%" style="text-align: center;">
                    <div class="form-title">Tourism Registrations &amp; Certificates</div>
                    <div class="form-title">RIGHT TO SERVICES (RTS)</div>
                    <div class="form-title">Application Form for the  {{ $application_form->name ?? '' }}</div>
                </td>
                <td width="20%" style="text-align: center;">
                    <div id="qrcode" class="qrcode"></div>
                </td>
            </tr>
        </table>

        <!-- Basic Information -->
        <div class="section-title">Basic Information</div>
        <table class="info-table">
            <tr>
                <th>Registration No:</th>
                <td>{{ $registration_id ?? '-' }}</td>

                <th>Status:</th>
                <td>{{ $status ?? '—' }}</td>
            </tr>
            <tr>
                <th>Candidate Name:</th>
                <td>{{ $applicant_name ?? '—' }}</td>
                <th>Date of Birth:</th>
                <td></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td>{{ $mobile ?? '—' }}</td>
                <th>Email:</th>
                <td>{{ $email ?? '—' }}</td>
            </tr>
            <tr>
                <th>Submitted Date:</th>
                <td>{{ $submitted_at ?? '—' }}</td>
                <th>Whatapps:</th>
                <td>{{ $whatsapp ?? '—' }}</td>
            </tr>
            <tr>
                <th>Address:</th>
                <td colspan="3">
                    {{ $applicant_address ?? '—' }}
                </td>
            </tr>

            <tr>
                <th>Business Name:</th>
                <td>{{ $company_name ?? '—' }}</td>
                <th>Address:</th>
                <td>

                </td>
            </tr>

            <tr>
                <th>Aadhar No:</th>
                <td>{{  '—' }}</td>
                <th>Udyam URN:</th>
                <td>{{  '—' }}</td>
            </tr>
            <tr>
                <th>Region:</th>
                <td>{{ $region ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $district ?? '—' }}</td>
            </tr>

            <tr>
                <th>Pan:</th>
                <td>
                    @if(!empty($Adventure_Application_data['pan_file']))
                        <img src="{{ asset('storage/' . $Adventure_Application_data['pan_file']) }}"
                             alt="Applicant PAN"
                             style="height:160px; border:1px solid #ccc; border-radius:8px;">
                    @else
                        <div style="height:160px; width:120px; background:#eee; display:flex; align-items:center; justify-content:center;">
                            <span>No PAN</span>
                        </div>
                    @endif
                </td>

                <th>Aadhar:</th>
                <td style="text-align: right;">
                    @if(!empty($Adventure_Application_data['aadhar_file']))
                        <img src="{{ asset('storage/' . $Adventure_Application_data['aadhar_file']) }}"
                             alt="Applicant Aadhar"
                             style="height:100px; border:1px solid #ccc; border-radius:8px;">
                    @else
                        <div style="height:100px; width:120px; background:#eee; display:flex; align-items:center; justify-content:center;">
                            <span>No Aadhar</span>
                        </div>
                    @endif
                </td>
            </tr>



        </table>


        <div class="section-title">Activity Details </div>
        <table class="info-table">
            <tr>
                <th>Adventure Activity Category:</th>
                <td>
                    {{ $adventure_category ?? '—' }}
                </td>
                <th>Activity Name:</th>
                <td>{{ $activity_name ?? '—' }}</td>
            </tr>


        </table>







        <!-- Print Buttons -->
        <div class="print-buttons no-print">
            <button class="btn btn-print" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print
            </button>
        </div>
    </div>

    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            var qrcode = new QRCode(document.getElementById("qrcode"), {

                width: 70,
                height: 70
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const size = 100;
        const container = document.getElementById('qrcode');
        Array.from(container.children).forEach(ch => {
            if (ch.tagName === 'CANVAS' || ch.tagName === 'IMG') container.removeChild(ch);
        });
        new QRCode(container, {
            text: url,
            width: size,
            height: size,
            correctLevel: QRCode.CorrectLevel.H
        });
    });
    </script>
</body>
</html>
