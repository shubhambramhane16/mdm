<!DOCTYPE html>
<html>

<head>
    <title>Tax Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        html,
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .invoice-container {
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background: #fff;">
    <div class="invoice-container">

        <div style="max-width: 800px; margin: auto; background: #fff; padding: 20px; border: 1px solid #ccc;">
            <table style="width: 100%; border-bottom: 1px solid #ccc;">
                <tr>
                    <td style="width: 46%;">
                        <img src="{{ public_path('media/logos/Group20.png') }}" alt="Logo" style="max-width: 105px;"><br>
                        <p style="margin: 5px 0; font-size: 12px;">B-111, Block A, Sector 64, Noida, Uttar Pradesh 201301
                        </p>
                    </td>
                    <td>
                        <h2 style="text-align: start;">TAX INVOICE</h2>
                        <p style="margin: 5px 0;text-align: right; font-size: 12px;line-height: 22px;">GSTIN :
                            123456789547<br>PAN : 123456789547</p>
                    </td>
                </tr>
            </table>

            <table style="width: 100%; margin-top: 20px; font-size: 12px;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        <p style="line-height: 25px; font-size: 12px;"><strong>Bill to :</strong><br>
                            {{ isset($invoice->client->name) ? $invoice->client->name : 'Client Name' }}<br>
                            {{ isset($invoice->client->address) ? $invoice->client->address : 'Client Address' }}<br>

                            GSTIN: {{ isset($invoice->client->gst) ? $invoice->client->gst : 'Client GST' }} | PAN :
                            {{ isset($invoice->client->pan) ? $invoice->client->pan : 'Client PAN' }}<br>
                            Contract Person :
                            {{ isset($invoice->client->contact_person_name) ? $invoice->client->contact_person_name : 'Contact Person' }}<br>
                            Mobile No. :
                            {{ isset($invoice->client->contact_person_mobile) ? $invoice->client->contact_person_mobile : 'Client Mobile' }}<br>
                            Email ID :
                            {{ isset($invoice->client->contact_person_email) ? $invoice->client->contact_person_email : 'Client Email' }}
                        </p>
                    </td>
                    <td style="vertical-align: top;">
                        <p style="line-height: 25px;font-size: 12px;color: #807b7b;">PO :<br>
                            Invoice No. : {{ $invoice->invoice_number ?? 'INV-001' }}<br>
                            Invoice Date
                            :{{ !empty($invoice->invoice_date) ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : \Carbon\Carbon::now()->format('d M Y') }}<br>
                            Due Date :
                            {{ !empty($invoice->due_date) ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : \Carbon\Carbon::now()->format('d M Y') }}
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Table -->
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px;">
                <tr style="background-color: #ba8733; color: white;">
                    <th style="padding: 10px; text-align: left;">Description / Style</th>
                    <th style="padding: 10px; text-align: left;">Qty.</th>
                    <th style="padding: 10px; text-align: left;">Rate (Rs.)</th>
                    <th style="padding: 10px; text-align: center;">Amount (Rs.)</th>
                </tr>
                @php
                    $items = json_decode($invoice->extra_info, true) ?? [];
                    $subtotal = 0;
                @endphp
                @forelse($items as $key => $item)
                    @php
                        $item_total = (float) ($item['quantity'] ?? 1) * (float) ($item['rate'] ?? 0);
                        $subtotal += $item_total;
                    @endphp
                    <tr @if ($key % 2 == 1) style="background-color: #f2f2f2;" @endif>
                        <td style="padding: 10px;">{{ $item['style_code'] ?? '-' }}</td>
                        <td style="padding: 10px;">{{ $item['quantity'] ?? 1 }}</td>
                        <td style="padding: 10px;">Rs.{{ number_format($item['rate'] ?? 0, 2) }}</td>
                        <td style="padding: 10px; text-align: center;">Rs.{{ number_format($item_total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 10px;">No items found.</td>
                    </tr>
                @endforelse
            </table>

            <!-- Account Details + Totals -->
            <table style="width: 100%; margin-top: 30px; font-size: 12px;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        <p style="font-style: italic; margin-bottom: 5px;"><strong>Account Details</strong></p>
                        <p style="line-height: 22px;">
                            @php $bankDetails = \App\Models\BankDetails::first(); @endphp
                            <strong>Account Number :</strong>
                            {{ $bankDetails->account_number ?? 'Account Number' }}<br>
                            <strong>Bank Name :</strong> {{ $bankDetails->bank_name ?? 'Bank Name' }}<br>
                            <strong>IFSC Code :</strong> {{ $bankDetails->ifsc_code ?? 'IFSC Code' }}
                        </p>
                    </td>
                    <td style="vertical-align: top;">
                        <table class="modern-summary-table" style="width: 100%; font-size: 12px;">
                            <tr>
                                <td class="label" style="text-align: right; padding-bottom: 0.6rem;">Subtotal</td>
                                <td class="text-right" style="text-align: right; padding-bottom: 0.6rem;">
                                    Rs.{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            @php
                                $gst_rate = $invoice->gst ?? 18;
                                $cgst_rate = $invoice->cgst ?? $gst_rate / 2;
                                $sgst_rate = $invoice->sgst ?? $gst_rate / 2;
                                $igst_rate = $invoice->igst ?? 0;

                                $cgst_amount = ($subtotal * $cgst_rate) / 100;
                                $sgst_amount = ($subtotal * $sgst_rate) / 100;
                                $igst_amount = ($subtotal * $igst_rate) / 100;

                                $gst_amount = $cgst_amount + $sgst_amount + $igst_amount;
                                $total_amount = $subtotal + $gst_amount;
                            @endphp
                            <tr>
                                <td class="label" style="text-align: right; padding-bottom: 0.6rem;">CGST
                                    ({{ $cgst_rate }}%)</td>
                                <td class="text-right" style="text-align: right; padding-bottom: 0.4rem;">
                                    Rs.{{ number_format($cgst_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="label" style="text-align: right; padding-bottom: 0.6rem;">SGST
                                    ({{ $sgst_rate }}%)</td>
                                <td class="text-right" style="text-align: right; padding-bottom: 0.4rem;">
                                    Rs.{{ number_format($sgst_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="label" style="text-align: right; padding-bottom: 0.6rem;">IGST
                                    ({{ $igst_rate }}%)</td>
                                <td class="text-right" style="text-align: right; padding-bottom: 0.4rem;">
                                    Rs.{{ number_format($igst_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="label" style="text-align: right; padding-bottom: 0.6rem;">GST Total</td>
                                <td class="text-right" style="text-align: right; padding-bottom: 0.4rem;">
                                    Rs.{{ number_format($gst_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <hr style="margin: 5px 0;">
                                </td>
                            </tr>
                            <tr class="total-row">
                                <td style="text-align: right; font-weight: bold;">GRAND TOTAL</td>
                                <td class="text-right" style="text-align: right; font-weight: bold;">
                                    Rs.{{ number_format($total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <hr style="margin: 5px 0;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>



            <table style="width: 100%; margin-top: 1rem; font-size: 12px;">
                <tr>
                    <td style="vertical-align: top;margin-top: 15rem;">
                        <p><strong>Terms & Conditions :</strong></p>
                        <ul style="padding-left: 18px; margin-top: 5px;">
                            <li style="padding-bottom: 0.5rem;">Payment is due upon receipt of this Invoice.</li>
                            <li style="padding-bottom: 0.5rem;">Late payment may incur additional charges.</li>
                            <li>Please make checks payable to your Graphic Design studio.</li>
                        </ul>
                    </td>
                    <td style="text-align: center; vertical-align: bottom;">
                        <p style="border-top: 1px solid #999; width: 200px; float: right; padding-top: 5px;">Authorised
                            Signatory</p>
                    </td>
                </tr>
            </table>

            <!-- Footer Contact -->
            <hr style="margin: 30px 0 10px 0;">
            <p style="text-align: center; font-size: 12px;">
                <strong>Mobile No. :</strong> +91-9599043602 |
                <strong>Email ID :</strong> abcd@gmail.com |
                <strong>Website :</strong> <a href="https://www.abym.in"
                    style="color: black; text-decoration: none;">https://www.abym.in</a>
            </p>

        </div>

    </div>
</body>

</html>
