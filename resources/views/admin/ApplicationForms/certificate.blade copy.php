<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration - {{ $application->registration_id }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background: #e9ecef;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .certificate-wrapper {
            background: #fff;
            width: 900px; /* A4 Landscape width approx */
            padding: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: relative;
        }

        .certificate-border {
            border: 5px solid #bfa15f; /* Gold border */
            padding: 5px;
            height: 100%;
            box-sizing: border-box;
        }

        .certificate-inner-border {
            border: 2px solid #333;
            padding: 20px 40px; /* Reduced top/bottom padding to move content up */
            height: 100%;
            position: relative;
            box-sizing: border-box;
            overflow: hidden; /* For watermark */
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.08;
            pointer-events: none;
            width: 80%;
            z-index: 0;
            text-align: center;
        }
        .watermark img {
            width: 600px;
            max-width: 100%;
        }

        .content-layer {
            position: relative;
            z-index: 1; /* Above watermark */
            text-align: center;
        }

        /* Header Logos */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo {
            height: 100px;
            object-fit: contain;
        }
        
        .header-title {
            text-align: center;
            flex-grow: 1;
        }
        .main-title {
            font-size: 38px;
            font-weight: 700;
            color: #bfa15f;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .sub-title {
            font-size: 20px;
            color: #555;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Body Content */
        .body-text {
            font-size: 20px;
            line-height: 1.8;
            color: #333;
            margin: 50px 0;
        }

        .highlight-name {
            font-size: 32px;
            font-weight: bold;
            color: #000;
            border-bottom: 2px solid #bfa15f;
            display: inline-block;
            padding: 0 20px 5px;
            margin: 10px 0;
            font-family: 'Georgia', serif; 
            min-width: 300px;
        }

        .reg-number {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid #ddd;
            background: #fdfdfd;
        }

        /* Footer */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
            text-align: left;
        }

        .qr-section {
            text-align: center;
        }
        .qr-code {
            margin-bottom: 10px;
        }

        .signature-section {
            text-align: center;
            width: 250px;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin: 10px 0;
        }
        .designation {
            font-weight: bold;
            font-size: 18px;
        }

        /* Print Actions */
        .actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        .btn {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .btn-print { background: #0d6efd; }
        .btn-back { background: #6c757d; }
        .btn:hover { opacity: 0.9; }

        @media print {
            @page {
                size: landscape;
                margin: 0;
            }
            body { 
                background: white; 
                margin: 0;
                padding: 0;
                height: 100%;
                width: 100%;
                display: block; /* Remove flex to avoid centering issues */
            }
            .certificate-wrapper { 
                box-shadow: none; 
                width: 296mm; /* A4 Landscape Width (leaving 1mm safety) */
                height: 208mm; /* A4 Landscape Height (leaving 2mm safety) */
                padding: 5mm; /* Fixed padding */
                margin: 0 auto;
                box-sizing: border-box;
                page-break-after: always;
                page-break-inside: avoid;
                overflow: hidden; /* Prevent spillover */
            }
            .certificate-border { border: 5px solid #bfa15f; height: 100%; width: 100%; box-sizing: border-box; }
            .certificate-inner-border { padding: 30px; height: 100%; box-sizing: border-box; } 
            .actions { display: none; }
        }
    </style>
</head>
<body>

    <div class="actions">
        <a href="{{ url()->previous() }}" class="btn btn-back">Go Back</a>
        <button onclick="window.print()" class="btn btn-print">Print / Save PDF</button>
    </div>

    <div class="certificate-wrapper">
        <div class="certificate-border">
            <div class="certificate-inner-border">
                
                <!-- Watermark -->
                <div class="watermark">
                    <img src="{{ asset('backend/mah-logo-300x277.png') }}" onerror="this.src='https://via.placeholder.com/300x277?text=Watermark'" alt="Watermark">
                </div>

                <div class="content-layer">
                    <!-- Header -->
                    <div class="header">
                        <!-- Left Logo -->
                        <img src="{{ asset('backend/mah-logo-300x277.png') }}" 
                             onerror="this.src='https://via.placeholder.com/100?text=Logo'" 
                             alt="MT Logo" class="logo">
                        
                        <!-- Title -->
                        <div class="header-title">
                            <div style="font-size: 24px; font-weight: bold; color: #333; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Government of Maharashtra</div>
                            <div class="sub-title" style="margin-bottom: 15px;">Maharashtra Tourism</div>
                            <h1 class="main-title">Certificate of Registration</h1>
                        </div>

                        <!-- Right Logo (Seal) -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Seal_of_Maharashtra.png?20170924125820" 
                             alt="Maharashtra Seal" class="logo">
                    </div>

                    <!-- Body -->
                    <div class="body-text">
                        <p>This is to certify that the application for <strong>{{ ucwords(str_replace('-', ' ', $type)) }}</strong> submitted by</p>
                        
                        <div class="highlight-name">{{ $application->applicant_name ?? $application->applicant->name ?? 'Applicant Name' }}</div>
                        
                        <p>has been successfully processed and approved in accordance with the regulations.</p>

                        <div class="reg-number">Registration Number: {{ $application->registration_id ?? 'N/A' }}</div>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <div class="qr-section">
                            <div class="qr-code">
                                {!! $qrCode !!}
                            </div>
                            <small>Scan to Verify</small>
                        </div>

                        <div class="signature-section">
                            <div style="height: 60px;">
                                <!-- Signature Image Place -->
                            </div>
                            <div class="signature-line"></div>
                            <div class="designation">Director</div>
                            <div>Maharashtra Tourism</div>
                            <div style="font-size: 14px; margin-top: 5px;">Date: {{ date('d M Y') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(request('print'))
    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500); 
        }
    </script>
    @endif

</body>
</html>