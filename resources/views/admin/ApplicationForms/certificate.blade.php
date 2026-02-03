<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Stay Registration Certificate</title>
    <style>
        /* CSS Reset for No Gaps */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0; /* Browser margin zero */
            }
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
            .cert-canvas {
                width: 297mm !important;
                height: 210mm !important;
                border: none !important;
                box-shadow: none !important;
            }
        }

        body {
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .no-print { margin-top: 20px; margin-bottom: 10px; }

        /* Center Green Button */
        .btn-print {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 40px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Full Body Border with No Gap */
        .cert-canvas {
            width: 297mm;
            height: 210mm;
            background-color: white;
            position: relative;
            padding: 5px; /* Bahut chota padding frame ke liye */
            overflow: hidden;
        }

        .outer-border {
            border: 3px solid #a67c00;
            height: 100%;
            width: 100%;
            padding: 3px;
            position: relative;
        }

        .inner-border {
            border: 8px double #a67c00; /* Richer Look */
            height: 100%;
            width: 100%;
            padding: 30px 60px;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Decorative Corners - Adjusted to stay inside the double border */
        .corner {
            position: absolute;
            width: 110px;
            height: 110px;
            z-index: 10;
        }
        .top-left { top: -5px; left: -5px; }
        .top-right { top: -5px; right: -5px; transform: rotate(90deg); }
        .bottom-left { bottom: -5px; left: -5px; transform: rotate(-90deg); }
        .bottom-right { bottom: -5px; right: -5px; transform: rotate(180deg); }

        /* Logos Header - Modified for 2 logos + center emblem */
        .logo-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            margin-bottom: 10px;
            position: relative;
        }

        .logo-left {
            flex: 1;
            text-align: right;
        }

        .logo-center {
            flex: 0 0 auto;
            text-align: center;
            position: relative;
        }

        .logo-right {
            flex: 1;
            text-align: left;
        }

        .logo-section img {
            height: 80px;
            object-fit: contain;
        }

        /* Center emblem with transparent background */
        .emblem {
            height: 70px;
            width: 70px;
            object-fit: contain;
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.2));
        }

        .header-text {
            text-align: center;
            margin-bottom: 10px;
        }
        .header-text h1 { font-size: 22px; font-weight: 500; text-transform: uppercase; line-height: 1.2; }
        .header-text h2 { font-size: 30px; color: #a67c00; font-weight: bold; margin-top: 5px; }

        /* Reg Box - Bold and Clear */
        .reg-line {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .reg-box {
            border: 2px solid #000;
            padding: 8px 20px;
            font-weight: bold;
            font-size: 18px;
            border-radius: 4px;
        }

        /* Optimized Content for Single Page */
        .main-content {
            font-size: 17.5px;
            line-height: 2.1; /* Adjusted for better fit */
            color: #222;
            flex-grow: 1;
        }

        .dots {
            border-bottom: 1px dotted #000;
            display: inline-block;
            font-weight: bold;
            padding: 0 5px;
        }

        /* Fixed Footer at the bottom of the inner border */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto; /* Pushes footer to bottom */
            padding-bottom: 10px;
        }

        .footer-left {
            font-weight: bold;
            font-size: 16px;
        }

        .footer-right {
            text-align: center;
            width: 320px;
        }
        .sign-line {
            border-top: 2px solid #000;
            margin-bottom: 5px;
        }
        .authority {
            font-weight: bold;
            font-size: 15px;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Certificate</button>
    </div>

    <div class="cert-canvas">
        <div class="outer-border">
            <div class="inner-border">
                <img src="https://png.pngtree.com/png-vector/20220526/ourmid/pngtree-european-pattern-golden-corner-decoration-png-image_4744516.png" class="corner top-left">
                <img src="https://png.pngtree.com/png-vector/20220526/ourmid/pngtree-european-pattern-golden-corner-decoration-png-image_4744516.png" class="corner top-right">
                <img src="https://png.pngtree.com/png-vector/20220526/ourmid/pngtree-european-pattern-golden-corner-decoration-png-image_4744516.png" class="corner bottom-left">
                <img src="https://png.pngtree.com/png-vector/20220526/ourmid/pngtree-european-pattern-golden-corner-decoration-png-image_4744516.png" class="corner bottom-right">

                <!-- <img src="https://i.ibb.co/Screenshot-2025-12-18-112811.png" class="corner top-left">
                <img src="https://i.ibb.co/Screenshot-2025-12-18-112811.png" class="corner top-right">
                <img src="https://i.ibb.co/Screenshot-2025-12-18-112811.png" class="corner bottom-left">
                <img src="https://i.ibb.co/Screenshot-2025-12-18-112811.png" class="corner bottom-right"> -->

                <!-- Modified Logo Section: 2 logos + center emblem -->
                <div class="logo-section">
                    <div class="logo-left">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Seal_of_Maharashtra.png" alt="Maharashtra Logo">

                    </div>

                    <div class="logo-center">
                        <img src="https://nielit.gov.in/sites/all/themes/berry/images/Emblem_of_India1.png" class="emblem" alt="Satyamev Jayate">
                        <!-- <img src="https://i.pinimg.com/736x/e2/a0/c9/e2a0c97029dfefe1f7b376f3cba9cc18.jpg" class="emblem" alt="Satyamev Jayate"> -->
                    </div>

                    <div class="logo-right">
                        <img src="https://rtsmt.vocmanindia.com/backend/mah-logo-300x277.png" alt="Seal of Maharashtra">
                    </div>
                </div>

                @php
                    $applicant_name= App\Models\Admin\ApplicationForm::where('id', $application->application_form_id)->first();
                @endphp
                {{-- <pre>
                    {{ print_r(json_decode($application, true), true) }}
                </pre> --}}
                <div class="header-text">
                    <h1>Directorate of Tourism</h1>
                    <h1>Divisional Tourism, Office, Pune</h1>
                    <h2>{{ $applicant_name->name ?? 'Applicant Name' }} Certificate</h2>
                </div>

                <div class="reg-line">
                    <div class="reg-box">Reg. No.: {{ $application->registration_id ?? ''}}</div>
                </div>

                <div class="main-content">
                    Project of Shri / Smt / Ms. <span class="dots" style="width: 600px;"></span><br>
                    is registered As Home Stay for tourists under Tourism policy Government Resolution No. TDS-2022/09/CR<br>
                    No 542/Tourism-4, Dated: 18 July, 2024.<br>

                    Details of Home Stay: <span class="dots" style="width: 700px;"></span><br>
                    Home Stay Name & Address: <span class="dots" style="width: 630px;"></span><br>

                    Mobile No. of Owner: <span class="dots" style="width: 250px;"></span> Email id of Owner: <span class="dots" style="width: 310px;"></span><br>
                    Aadhar No. of Owner: <span class="dots" style="width: 350px;"></span> Total Area of Land (sq.ft./sq m) <span class="dots" style="width: 200px;"></span><br>

                    Total Rooms No: <span class="dots" style="width: 300px;"></span> Dormitory No.: <span class="dots" style="width: 330px;"></span><br>
                    Name of Town / Village: <span class="dots" style="width: 350px;"></span> Survey No. <span class="dots" style="width: 310px;"></span><br>

                    Registration Period: Date <span class="dots" style="width: 180px;"></span> to <span class="dots" style="width: 180px;"></span>
                </div>

                <div class="footer">
                    <div class="footer-left">
                        Stamp <br><br>
                        Date: <span class="dots" style="min-width: 130px;"></span>
                    </div>
                    <div class="footer-right">
                        <div class="sign-line"></div>
                        <div class="authority">
                            Dy. Director Directorate of <br>
                            Tourism Regional Office, Pune
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
