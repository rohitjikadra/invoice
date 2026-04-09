<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 4mm; size: A4 portrait; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .sheet { border: 1px solid #222; padding: 3px; }
        .sheet-inner { border: 1px solid #222; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .p8 { padding: 8px; }
        .title { font-size: 28px; color: #1a237e; font-weight: 700; line-height: 1; }
        .line { border-top: 1px solid #333; }
        table { width: 100%; border-collapse: collapse; }
        .split td { border-right: 1px solid #333; vertical-align: top; }
        .split td:last-child { border-right: none; }
        .cell { padding: 6px; }
        .items th, .items td { border: 1px solid #333; padding: 3px; }
        .items th { background: #efefef; }
        .items th:first-child, .items td:first-child { border-left: none; }
        .items th:last-child, .items td:last-child { border-right: none; }
        .mini-table td { border: 1px solid #333; padding: 3px; }
        .mini-table td:first-child { border-left: none; }
        .mini-table tr:first-child td { border-top: none; }
        .mini-table tr:last-child td { border-bottom: none; }
        .totals td { border: 1px solid #333; padding: 5px; }
        .totals td:first-child { border-left: none; }
        .totals tr:first-child td { border-top: none; }
        .totals tr:last-child td { border-bottom: none; }
        .small { font-size: 10px; }
        .xsmall { font-size: 9px; }
        .blue { color: #0d47a1; }
        .red { color: #c62828; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    $settings = \App\Models\Setting::getCached();
    $companyName = $settings->company_name ?: 'COMPANY NAME';
    $companyAddress = $settings->company_address ?: '';
    $logoDataUri = null;
    $logoPath = public_path('images/logo.png');
    if (file_exists($logoPath)) {
        $extension = pathinfo($logoPath, PATHINFO_EXTENSION) ?: 'png';
        $logoDataUri = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    // Force single-page PDF: cap line items displayed.
    $maxItems = 7;
    $displayItems = $invoice->items->take($maxItems);
    $hiddenItemCount = max(0, $invoice->items->count() - $displayItems->count());
@endphp
<div class="sheet">
    <div class="sheet-inner">
    <div class="p8 no-break" style="min-height: 82px; padding-top: 10px; padding-bottom: 10px; position: relative;">
        @if($logoDataUri)
            <img src="{{ $logoDataUri }}" alt="Logo" style="position:absolute; left:8px; top:10px; width:58px; height:58px; border-radius:50%; object-fit:cover;">
        @endif

        <div class="center" style="padding-left:90px; padding-right:90px;">
            <div class="title">{{ $companyName }}</div>
            <div>{{ $companyAddress }}</div>
            <div>{{ $settings->company_email }} @if($settings->company_mobile) (MO): {{ $settings->company_mobile }} @endif</div>
        </div>
    </div>

    <table class="split line no-break">
        <tr>
            <td class="cell bold">GST No.: {{ $settings->gst_no }}</td>
            <td class="cell center bold">State &amp; Code : {{ $settings->state_code }} - {{ $settings->state_name }}</td>
            <td class="cell right bold">PAN : {{ $settings->pan_no }}</td>
        </tr>
    </table>
    <div class="center bold line" style="padding:4px 0;font-size:20px;">TAX INVOICE</div>

    <table class="split line no-break">
        <tr>
            <td class="cell" style="width:68%;">
                <div class="red">Detail Of Receivers/Billed To.</div>
                <div class="bold blue" style="font-size:15px;">{{ strtoupper($invoice->customer?->name ?? '') }}</div>
                <div>{{ $invoice->customer?->address }}</div>
                <div style="margin-top:6px;"><span class="bold">GSTIN :</span></div>
                <div style="margin-top:6px;"><span class="bold">State :</span> <span class="bold">GUJARAT-GUJARAT</span></div>
            </td>
            <td class="cell" style="width:32%;">
                <div class="bold">Invoice Detail:</div>
                <div style="font-size:17px;" class="bold">Invoice No. <span class="red">: {{ preg_replace('/[^0-9]/', '', $invoice->invoice_number) ?: $invoice->id }}</span></div>
                <div>Invoice Date : {{ $invoice->invoice_date?->format('d/m/Y') }}</div>
                <div>Challan No : {{ $invoice->id }}</div>
                <div>Challan Date : {{ $invoice->invoice_date?->format('d/m/Y') }}</div>
                <div>Due Day-Dt : {{ \Carbon\Carbon::parse($invoice->invoice_date)->diffInDays($invoice->due_date) }} - {{ $invoice->due_date?->format('d/m/Y') }}</div>
                <div>Agent : {{ $settings->agent_name }}</div>
            </td>
        </tr>
    </table>

    <table class="split line no-break">
        <tr>
            <td class="cell">Transporter :</td>
            <td class="cell">L.R No :<br>L.R Date : {{ $invoice->invoice_date?->format('d/m/Y') }}</td>
            <td class="cell">Veh.No. :</td>
        </tr>
    </table>

    <table class="items no-break">
        <thead>
        <tr>
            <th style="width:5%;">Sr.</th>
            <th style="width:34%; text-align:left;">Description Of Goods</th>
            <th style="width:8%;">HSN</th>
            <th style="width:8%;">Pcs</th>
            <th style="width:8%;">Cut</th>
            <th style="width:10%;">Meter</th>
            <th style="width:7%;">UQC</th>
            <th style="width:10%;">RATE</th>
            <th style="width:10%;">AMOUNT</th>
        </tr>
        </thead>
        <tbody>
        @foreach($displayItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td class="center">540752</td>
                <td class="center">{{ number_format((float) $item->quantity, 0) }}</td>
                <td class="center"></td>
                <td class="center">{{ number_format((float) $item->quantity * 89.96, 1) }}</td>
                <td class="center">MTR</td>
                <td class="right">{{ number_format((float) $item->price, 2) }}</td>
                <td class="right">{{ number_format((float) $item->total, 2) }}</td>
            </tr>
        @endforeach
        @if($hiddenItemCount > 0)
            <tr>
                <td class="bold center">...</td>
                <td colspan="8" class="bold">+{{ $hiddenItemCount }} more item(s) not shown (single-page PDF).</td>
            </tr>
        @endif
        @for($i = $displayItems->count() + ($hiddenItemCount > 0 ? 1 : 0); $i < 7; $i++)
            <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        @endfor
        <tr class="bold">
            <td colspan="3">Total ....</td>
            <td class="center">{{ number_format((float) $invoice->items->sum('quantity'), 0) }}</td>
            <td></td>
            <td class="center">{{ number_format((float) $invoice->items->sum('quantity') * 89.96, 1) }}</td>
            <td colspan="2"></td>
            <td class="right">{{ number_format((float) $invoice->subtotal, 2) }}</td>
        </tr>
        </tbody>
    </table>

    <table class="split no-break">
        <tr>
            <td class="cell" style="width:63%;">
                <div class="bold" style="text-decoration:underline;">Bank Details</div>
                <div>Bank : <span class="bold">{{ $settings->bank_name }}</span></div>
                <div>A/c No : <span class="bold">{{ $settings->bank_account_no }}</span></div>
                <div>IFSC : <span class="bold">{{ $settings->bank_ifsc }}</span></div>
                <div>Branch : <span class="bold">{{ $settings->bank_branch }}</span></div>
            </td>
            <td class="cell" style="width:37%; padding: 0 0 0 0;">
                <table class="totals" style="width:100%; margin:0;">
                    <tr><td class="bold">Sub Total ....</td><td class="right bold">{{ number_format((float) $invoice->subtotal, 2) }}</td></tr>
                    <tr><td>Tax ({{ number_format((float) $invoice->tax_percentage, 2) }}%) (+)</td><td class="right">{{ number_format((float) $invoice->tax_amount, 2) }}</td></tr>
                    <tr><td>Other Less (-)</td><td class="right">0.00</td></tr>
                    <tr>
                        <td class="bold blue" style="font-size:15px; white-space:nowrap;">Net Amount :</td>
                        <td class="right bold blue" style="font-size:15px; white-space:nowrap;">{{ number_format((float) $invoice->total_amount, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @php
        $numberToWords = function (int $number) use (&$numberToWords): string {
            $ones = [
                0 => '',
                1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
                6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten',
                11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
                15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
            ];
            $tens = [
                2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
                6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety',
            ];

            if ($number < 20) {
                return $ones[$number];
            }
            if ($number < 100) {
                return trim($tens[intdiv($number, 10)] . ' ' . $ones[$number % 10]);
            }
            if ($number < 1000) {
                return trim($ones[intdiv($number, 100)] . ' hundred ' . $numberToWords($number % 100));
            }
            if ($number < 100000) {
                return trim($numberToWords(intdiv($number, 1000)) . ' thousand ' . $numberToWords($number % 1000));
            }
            if ($number < 10000000) {
                return trim($numberToWords(intdiv($number, 100000)) . ' lakh ' . $numberToWords($number % 100000));
            }

            return trim($numberToWords(intdiv($number, 10000000)) . ' crore ' . $numberToWords($number % 10000000));
        };

        $amountInWords = $numberToWords((int) round((float) $invoice->total_amount));
    @endphp
    <div class="line p8 bold no-break">
        IN WORDS : RUPEES {{ strtoupper($amountInWords) }} ONLY.
    </div>

    <table class="split line no-break">
        <tr>
            <td class="cell" style="width:63%;">
                <div><span class="red bold">Del.(Detial of Consinee)</span> <span class="blue bold" style="font-size:14px; margin-left:12px;">SAKHI SAHELI</span></div>
                <div style="margin-top:6px;">Address...:</div>
                <div>{{ $invoice->customer?->address }}</div>
                <div style="margin-top:6px;">State...: <span class="bold">GUJARAT-GUJARAT</span></div>
                <div>GST No...:</div>
            </td>
            <td class="cell" style="width:37%;padding: 0 0 0 0;">
                <table class="mini-table">
                    <tr><td>Date</td></tr>
                    <tr><td>Chq. No.</td></tr>
                    <tr><td>Amt</td></tr>
                    <tr><td>Bank</td></tr>
                    <tr><td>Bill No.</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="split line no-break">
        <tr>
            <td class="cell xsmall" style="width:46%;">
                <div class="bold">Terms and Conditions :</div>
                @if($settings->terms)
                    {!! nl2br(e(\Illuminate\Support\Str::limit($settings->terms, 380))) !!}
                @else
                    <div>1) The goods are dispatched on your account and at your risk &amp; responsibility.</div>
                    <div>2) Any complaint regarding goods should be reported in writing within 24 hours of receipt.</div>
                    <div>3) Goods sold will not be taken back.</div>
                    <div>4) Payment will be accepted only by A/c Payee's Draft/Cheque.</div>
                    <div>5) Interest at 2.0% per month charged on due course.</div>
                    <div>6) No Dyeing guarantee.</div>
                @endif
                <div class="red bold" style="margin-top:8px; font-size:12px;">Subject to SURAT Jurisdiction Only. E. &amp; O. E.</div>
            </td>
            <td class="cell center bold" style="width:17%; vertical-align:bottom; font-size:13px;">Receiver's Sign</td>
            <td class="cell right bold" style="width:37%; vertical-align:bottom; font-size:13px;">
                <span class="red">For, {{ $companyName }}</span><br><br>
                Authorised Signatory
            </td>
        </tr>
    </table>
    </div>
</div>
</body>
</html>
