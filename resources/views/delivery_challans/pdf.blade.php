<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Challan {{ $deliveryChallan->challan_number }}</title>
    <style>
        @page { margin: 2mm; size: A4 portrait; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .sheet { padding: 0; }
        .sheet-inner { border: none; }
        .page-frame { min-height: 287mm; }
        .chunk-section {
            page-break-inside: avoid;
            border: 1px solid #222;
            padding: 3px;
            margin-bottom: 4px;
            box-sizing: border-box;
        }
        .chunk-section:last-child { margin-bottom: 0; }
        .chunk-section-inner { border: none; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .line { border-top: 1px solid #333; }
        .red { color: #cc0000; }
        table { width: 100%; border-collapse: collapse; }
        .split td { border-right: 1px solid #333; vertical-align: top; }
        .split td:last-child { border-right: none; }
        .no-vertical td { border-right: none !important; }
        .gstin-row td { border-right: none !important; }
        .state-code-cell { vertical-align: middle !important; }
        .challan-title-row td { border-right: none !important; }
        .cell { padding: 5px; }
        .mini td { border: 1px solid #333; padding: 3px 6px; }
        .meter-head th {
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            border-right: 1px solid #333;
            padding: 3px 8px;
            text-align: left;
            width: 25%;
            white-space: nowrap;
        }
        .meter-head th:last-child { border-right: none; }
        .meter-block td { border-right: 1px solid #333; padding: 2px 8px; vertical-align: top; width: 25%; }
        .meter-block td:last-child { border-right: none; }
        .meter-line { padding: 2px 0; white-space: nowrap; }
        .meter-line .sr { display: inline-block; width: 22px; font-weight: 700; text-align: left; }
        .meter-line .meter { display: inline-block; width: 72px; text-align: left; padding-left: 8px; }
        .meter-col-totals td {
            border-top: 1px solid #333;
            border-right: 1px solid #333;
            padding: 2px 8px;
            text-align: center;
            font-weight: 700;
            width: 25%;
            white-space: nowrap;
        }
        .meter-col-totals td:last-child { border-right: none; }
        .totals td { border-top: 1px solid #333; padding: 5px; }

        /* Tight mode: keep up to 100 entries on one page */
        .single-page { font-size: 10px; line-height: 1.15; }
        .single-page .sheet { padding: 2px; }
        .single-page .cell { padding: 3px; }
        .single-page .meter-head th { padding: 2px 3px; }
        .single-page .meter-block td { padding: 2px 5px; }
        .single-page .totals td { padding: 3px; }
        .single-page .title-main { font-size: 16px !important; }
        .single-page .sub-head { font-size: 12px !important; }
        .single-page .challan-no { font-size: 16px !important; }
        .single-page .page-frame { min-height: 287mm; height: 287mm; }
        .single-page .chunk-section.two-up { min-height: 49.5%; }
        .single-page .chunk-section.two-up .split,
        .single-page .chunk-section.two-up .meter-head,
        .single-page .chunk-section.two-up .meter-block { margin: 0; }
        .remark-row td {
            padding-top: 25px !important;
            padding-bottom: 3px !important;
            font-size: 9px;
        }
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

    $meterChunks = $deliveryChallan->meters->values()->chunk(50);
    $forceSinglePage = $deliveryChallan->meters->count() <= 100;
@endphp

<div class="{{ $forceSinglePage ? 'single-page' : '' }}">
    <div class="sheet">
        <div class="sheet-inner page-frame">
        @foreach($meterChunks as $chunkIndex => $chunk)
            @php
                $chunkStartSr = (int) ($chunk->first()->sr_no ?? 1);
                $perColumn = (int) ceil(max(1, $chunk->count()) / 4);
                $columns = $chunk->chunk($perColumn);
                while ($columns->count() < 4) {
                    $columns->push(collect());
                }
            @endphp

            <div class="chunk-section {{ $forceSinglePage && $meterChunks->count() === 2 ? 'two-up' : '' }}">
            <div class="chunk-section-inner">
            <div style="position: relative; min-height: {{ $forceSinglePage ? '48px' : '78px' }}; padding: {{ $forceSinglePage ? '4px 60px' : '8px 90px' }};" class="center">
                <div class="title-main" style="font-size:22px; font-weight:700;">{{ $companyName }}</div>
                <div>{{ $companyAddress }}</div>
                <div>{{ $settings->company_mobile }}</div>
            </div>

            <table class="split gstin-row">
                <tr>
                    <td class="cell bold">GSTIN : {{ $settings->gst_no }}</td>
                    <td class="cell center bold state-code-cell">STATE &amp; CODE : {{ $settings->state_code }}-{{ $settings->state_name }}</td>
                    <td class="cell right bold"></td>
                </tr>
            </table>

            <table class="split line challan-title-row">
                <tr>
                    <td class="cell bold">Vehicle No : {{ $deliveryChallan->vehicle_no }}</td>
                    <td class="cell center bold sub-head" style="font-size:16px; font-style: italic;">DELIVERY CHALLAN</td>
                    <td class="cell bold">EwayBill : {{ $deliveryChallan->eway_bill_no }}</td>
                </tr>
            </table>

            <table class="split line">
                <tr>
                    <td class="cell" style="width:34%;">
                        <div class="red bold" style="font-size:{{ $forceSinglePage ? '11px' : '13px' }};">{{ strtoupper($deliveryChallan->receiver_name) }}</div>
                        <div>{{ $deliveryChallan->receiver_address }}</div>
                        <div class="bold" style="margin-top:8px;">GSTIN : {{ $deliveryChallan->receiver_gstin }}</div>
                    </td>
                    <td class="cell" style="width:33%;">
                        <div class="bold" style="font-size:{{ $forceSinglePage ? '11px' : '13px' }};">{{ strtoupper($deliveryChallan->consignee_name) }}</div>
                        <div>{{ $deliveryChallan->consignee_address }}</div>
                        <div class="bold" style="margin-top:8px;">GSTIN : {{ $deliveryChallan->consignee_gstin }}</div>
                    </td>
                    <td class="cell" style="width:33%;">
                        <div class="bold challan-no" style="font-size:14px;">Challan No <span class="red" style="font-size:{{ $forceSinglePage ? '18px' : '20px' }};">: {{ preg_replace('/[^0-9]/', '', $deliveryChallan->challan_number) ?: $deliveryChallan->id }}</span></div>
                        <div class="bold">Date <span style="margin-left:24px;">: {{ $deliveryChallan->challan_date?->format('d-m-y') }}</span></div>
                        <div class="bold">Quality <span style="margin-left:8px;">: {{ strtoupper($deliveryChallan->quality) }}</span></div>
                        <div class="bold">Broker <span style="margin-left:14px;">: {{ strtoupper($deliveryChallan->broker) }}</span></div>
                    </td>
                </tr>
            </table>

            <table class="meter-head">
                <tr>
                    <th>Sr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Meter</th>
                    <th>Sr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Meter</th>
                    <th>Sr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Meter</th>
                    <th>Sr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Meter</th>
                </tr>
            </table>

            <table class="meter-block">
                <tr>
                    @foreach($columns as $col)
                        <td>
                            @foreach($col as $row)
                                <div class="meter-line">
                                    <span class="sr">{{ ($row->sr_no - $chunkStartSr) + 1 }}</span>
                                    <span class="meter">{{ number_format((float) $row->meter, 2) }}</span>
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            </table>

            <table class="meter-col-totals">
                <tr>
                    @foreach($columns as $col)
                        <td>{{ number_format((float) $col->sum('meter'), 2) }}</td>
                    @endforeach
                </tr>
            </table>

            <table class="split totals no-vertical">
                <tr>
                    <td class="cell bold">
                        Total Pcs : {{ number_format((float) $chunk->count(), 2) }},
                        Meter : {{ number_format((float) $chunk->sum('meter'), 2) }}
                    </td>
                    <td class="cell right bold">For. {{ strtoupper($companyName) }}</td>
                </tr>
            </table>

            <table class="split no-vertical remark-row">
                <tr>
                    <td class="cell bold" style="width:40%;">Remark : {{ $deliveryChallan->remark }}</td>
                    <td class="cell center bold" style="width:20%; vertical-align:bottom;">Buyer&apos;s Sign.</td>
                    <td class="cell center bold" style="width:20%; vertical-align:bottom;">Checked By</td>
                    <td class="cell center bold" style="width:20%; vertical-align:bottom;">Authorise Signatory</td>
                </tr>
            </table>
            </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
</body>
</html>
