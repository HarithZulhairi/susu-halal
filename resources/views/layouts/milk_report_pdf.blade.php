<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipients Record - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px; /* Slightly smaller font to fit more columns */
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-decoration: none;
        }
        
        .logo {
            display: block;
            margin: 0 auto 10px auto; 
            max-width: 300px; 
            height: auto;
        }
        
        /* Patient Info Header */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 2px solid #000;
            font-size: 12px;
        }
        .info-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 18%;
        }
        
        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }
        .data-table th {
            border: 1px solid #000;
            background-color: #e0e0e0;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 10px; /* Smaller header font */
            vertical-align: middle;
        }
        .data-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 11px;
            height: 25px; 
        }
        
        /* Adjusted Column Widths for 9 Columns */
        .col-date   { width: 10%; }
        .col-batch  { width: 10%; }
        .col-donor  { width: 10%; } /* New */
        .col-consent{ width: 10%; } /* New */
        .col-freq   { width: 8%; }
        .col-amt    { width: 8%; }
        .col-sig    { width: 12%; }
        .col-remark { width: 20%; }

        .red-line { border-bottom: 2px solid red; }
        .text-red { color: red; }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        /* Print Settings */
        @media print {
            @page { margin: 0.5cm; }
            body { padding: 0; }
            .no-print { display: none; }
        }
        
        .btn-print {
            padding: 10px 20px;
            background-color: #1A5F7A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <button class="btn-print no-print" onclick="window.print()">
        <i class="fa fa-print"></i> Print / Save as PDF
    </button>

    <img src="{{ asset('images/logo_iium.png') }}" alt="Sultan Ahmad Shah Medical Centre Logo" class="logo">
    <div class="header">SULTAN AHMAD SHAH MEDICAL CENTRE @IIUM</div>
    <div class="header">DONOR EXPRESS BREAST MILK (DEBM) </div>
    <div class="header">RECIPIENTS RECORD</div>

    <table class="info-table">
        <tr>
            <td class="label">NAME</td>
            <td style="width: 30%; font-weight: bold;">BABY ADAM</td>
            <td class="label">PATIENT ID (MRN)</td>
            <td style="font-weight: bold;">P-2024-001</td>
        </tr>
        <tr>
            <td class="label">DOB / TOB</td>
            <td>15-01-2026 / 08:30 AM</td>
            <td class="label">NICU LOCATION</td>
            <td>NICU-A1</td>
        </tr>
        <tr>
            <td class="label">GESTATIONAL AGE</td>
            <td>32 Weeks</td>
            <td class="label">DAY OF LIFE</td>
            <td>7</td>
        </tr>
        <tr>
            <td class="label">DISPENSING METHOD</td>
            <td>Milk Kinship (Full Nursing)</td>
            <td class="label">FEEDING SCHEDULE</td>
            <td>Every 3 Hours</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-date">DATE/TIME</th>
                <th class="col-batch">BATCH NO</th>
                <th class="col-donor">DONOR ID</th>
                <th class="col-consent">CONSENT<br>(KINSHIP)</th>
                <th class="col-freq">FREQ</th>
                <th class="col-amt">AMOUNT</th>
                <th class="col-sig">INCHARGE</th>
                <th class="col-sig">WITNESS</th>
                <th class="col-remark">REMARK / <br>(TYPE OF FEEDING)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>21/01/26<br>08:00 H</td>
                <td>M26-001</td>
                <td>D-2024-55</td>
                <td>YES</td>
                <td>3 H</td>
                <td>30 mls</td>
                <td>Nurse Joy</td>
                <td>Dr. Strange</td>
                <td>OGT</td>
            </tr>
            <tr>
                <td>21/01/26<br>11:00 H</td>
                <td>M26-002</td>
                <td>D-2024-55</td>
                <td>YES</td>
                <td>3 H</td>
                <td>30 mls</td>
                <td>Nurse Joy</td>
                <td>Nurse Carla</td>
                <td>OGT</td>
            </tr>
            <tr>
                <td>21/01/26<br>14:00 H</td>
                <td>M26-005</td>
                <td>D-2024-88</td>
                <td>NO</td>
                <td>3 H</td>
                <td>30 mls</td>
                <td>Nurse Joy</td>
                <td>Nurse Carla</td>
                <td>OGT</td>
            </tr>
            
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL:</td>
                <td class="text-red" style="font-weight: bold; border-bottom: 2px solid red;">90 mls</td>
                <td colspan="3"></td>
            </tr>

            @for($i = 0; $i < 15; $i++)
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        Sultan Ahmad Shah Medical Centre @IIUM, Jalan Sultan Ahmad Shah, Bandar Indera Mahkota, 25200 Kuantan, Pahang Darul Makmur.<br>
        Tel: 09-591 2500
    </div>

    <script>
        window.onload = function() {
            // setTimeout(() => window.print(), 500);
        };
    </script>
</body>