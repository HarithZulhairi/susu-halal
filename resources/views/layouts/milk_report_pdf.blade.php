<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipients Record - {{ $generatedDate }}</title>
    <style>
        /* ... (Keep your existing CSS exactly as is) ... */
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 20px; color: #000; }
        .header { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 5px; text-transform: uppercase; }
        .logo { display: block; margin: 0 auto 10px auto; max-width: 300px; height: auto; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; border: 2px solid #000; font-size: 12px; }
        .info-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        .label { font-weight: bold; background-color: #f0f0f0; width: 18%; }
        .data-table { width: 100%; border-collapse: collapse; border: 2px solid #000; }
        .data-table th { border: 1px solid #000; background-color: #e0e0e0; padding: 6px 4px; text-align: center; font-weight: bold; font-size: 10px; vertical-align: middle; }
        .data-table td { border: 1px solid #000; padding: 4px; text-align: center; font-size: 11px; height: 25px; }
        .col-date { width: 10%; } .col-batch { width: 10%; } .col-donor { width: 10%; } .col-consent{ width: 10%; } 
        .col-freq { width: 8%; } .col-amt { width: 8%; } .col-sig { width: 12%; } .col-remark { width: 20%; }
        .red-line { border-bottom: 2px solid red; }
        .text-red { color: red; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; border-top: 1px solid #ccc; padding-top: 5px; }
        @media print { @page { margin: 0.5cm; } body { padding: 0; } .no-print { display: none; } }
        .btn-print { padding: 10px 20px; background-color: #1A5F7A; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>

    <button class="btn-print no-print" onclick="window.print()">
        <i class="fa fa-print"></i> Print / Save as PDF
    </button>

    <img src="{{ asset('images/logo_iium.png') }}" alt="Logo" class="logo">
    <div class="header">SULTAN AHMAD SHAH MEDICAL CENTRE @IIUM</div>
    <div class="header">DONOR EXPRESS BREAST MILK (DEBM) </div>
    <div class="header" style="margin-bottom: 15px;">RECIPIENTS RECORD</div>

    {{-- DYNAMIC PATIENT INFO --}}
    <table class="info-table">
        <tr>
            <td class="label">NAME</td>
            <td style="width: 30%; font-weight: bold;">{{ strtoupper($patient->pr_BabyName) }}</td>
            <td class="label">PATIENT ID (MRN)</td>
            <td style="font-weight: bold;">{{ $patient->formatted_id ?? $patient->pr_ID }}</td>
        </tr>
        <tr>
            <td class="label">DOB / TOB</td>
            {{-- Assuming DOB is stored as Y-m-d --}}
            <td>{{ \Carbon\Carbon::parse($patient->pr_BabyDOB)->format('d/m/Y') }}</td>
            <td class="label">NICU LOCATION</td>
            <td>{{ $patient->pr_NICU }}</td>
        </tr>
        <tr>
            <td class="label">GESTATIONAL AGE</td>
            <td>{{ $gestationalAge }} Weeks</td>
            <td class="label">GENDER</td>
            <td>{{ $patient->pr_BabyGender }}</td>
        </tr>
        <tr>
            <td class="label">CONSENT STATUS</td>
            <td>{{ $patient->pr_ConsentStatus }}</td>
            <td class="label">REPORT DATE</td>
            <td>{{ now()->format('d M Y') }}</td>
        </tr>
    </table>

    {{-- DYNAMIC DATA TABLE --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-date">DATE/TIME</th>
                <th class="col-batch">BATCH NO<br>(MILK ID)</th>
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
            @foreach($reportData as $row)
            <tr>
                <td>{{ $row->date }}<br>{{ $row->time }}</td>
                <td>{{ $row->batch_no }}</td>
                <td>{{ $row->donor_id }}</td>
                <td>{{ $row->consent_kinship }}</td>
                <td>{{ $row->freq }}</td>
                <td>{{ $row->amount }} mL</td>
                <td>{{ $row->incharge }}</td>
                <td>{{ $row->witness }}</td>
                <td>{{ $row->remark }}</td>
            </tr>
            @endforeach
            
            {{-- Total Row --}}
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL:</td>
                <td class="text-red" style="font-weight: bold; border-bottom: 2px solid red;">{{ $totalVolume }} mL</td>
                <td colspan="3"></td>
            </tr>

            {{-- Fill remaining rows to look like a full sheet --}}
            @for($i = 0; $i < max(5, 15 - count($reportData)); $i++)
            <tr>
                <td>&nbsp;</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
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
</html>