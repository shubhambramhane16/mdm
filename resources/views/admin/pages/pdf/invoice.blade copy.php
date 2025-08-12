<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #INV-001</title>
     <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            color: #222;
        }

        .modern-invoice-container {
            max-width: 850px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12);
            padding: 40px 50px 30px 50px;
        }

        .modern-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #3a86ff;
            padding-bottom: 18px;
            margin-bottom: 32px;
        }

        .modern-company {
            font-size: 32px;
            font-weight: 700;
            color: #3a86ff;
            letter-spacing: 2px;
        }

        .modern-invoice-title {
            text-align: right;
        }

        .modern-invoice-title h2 {
            font-size: 38px;
            color: #222;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .modern-status {
            display: inline-block;
            padding: 7px 22px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            background: #e0e7ff;
            color: #3a86ff;
            border: 1.5px solid #3a86ff;
            margin-top: 6px;
        }

        .modern-status.paid {
            background: #d1fae5;
            color: #059669;
            border-color: #059669;
        }

        .modern-status.pending {
            background: #fef9c3;
            color: #b45309;
            border-color: #b45309;
        }

        .modern-status.overdue {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #b91c1c;
        }

        .modern-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 30px;
        }

        .modern-block {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 22px 24px;
            flex: 1;
            min-width: 260px;
        }

        .modern-block h4 {
            color: #3a86ff;
            font-size: 17px;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .modern-block .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            font-size: 15px;
        }

        .modern-block .label {
            color: #666;
            font-weight: 500;
        }

        .modern-block .value {
            color: #222;
            font-weight: 600;
        }

        .modern-items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 35px 0 0 0;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px #e0e7ff;
        }

        .modern-items-table th {
            background: #3a86ff;
            color: #fff;
            padding: 16px 10px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .modern-items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #e0e7ff;
            font-size: 15px;
        }

        .modern-items-table tr:last-child td {
            border-bottom: none;
        }

        .modern-items-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .modern-summary {
            margin-top: 35px;
            display: flex;
            justify-content: flex-end;
        }

        .modern-summary-table {
            width: 340px;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 4px #e0e7ff;
        }

        .modern-summary-table td {
            padding: 13px 18px;
            border: 1px solid #e0e7ff;
            font-size: 15px;
        }

        .modern-summary-table .label {
            background: #f1f5f9;
            font-weight: 600;
            color: #555;
        }

        .modern-summary-table .total-row {
            background: #3a86ff;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
        }

        .modern-payment {
            margin-top: 40px;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 24px 28px;
        }

        .modern-payment h4 {
            color: #3a86ff;
            margin-bottom: 12px;
            font-size: 17px;
        }

        .modern-bank-details {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            font-size: 15px;
        }

        .modern-bank-details>div {
            flex: 1;
            min-width: 200px;
        }

        .modern-terms {
            margin-top: 32px;
            background: #fffbe7;
            border: 1px solid #ffe066;
            border-radius: 10px;
            padding: 22px 28px;
        }

        .modern-terms h4 {
            color: #b45309;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .modern-terms ul {
            margin-left: 18px;
        }

        .modern-terms li {
            color: #b45309;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .modern-signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 45px;
            padding-top: 18px;
        }

        .modern-signature-box {
            text-align: center;
            width: 220px;
        }

        .modern-signature-line {
            border-top: 1.5px solid #3a86ff;
            margin-top: 55px;
            padding-top: 7px;
            font-size: 13px;
            color: #3a86ff;
            font-weight: 600;
        }

        .modern-footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .modern-invoice-container {
                padding: 20px 5vw;
            }

            .modern-meta {
                flex-direction: column;
                gap: 18px;
            }

            .modern-bank-details {
                flex-direction: column;
                gap: 10px;
            }

            .modern-signature-section {
                flex-direction: column;
                gap: 30px;
            }

            .modern-summary-table {
                width: 100%;
            }
        }

        @media print {
            .modern-invoice-container,
            .modern-header,
            .modern-block,
            .modern-items-table th,
            .modern-summary-table .total-row,
            .modern-status,
            .modern-status.paid,
            .modern-status.pending,
            .modern-status.overdue,
            .modern-payment,
            .modern-terms {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    <div class="modern-invoice-container">
        <!-- Header -->
        <div class="modern-header">
            <div>
                <div class="modern-company">Your Company Name</div>
                <div style="font-size:15px; color:#666; margin-top:6px;">
                    B-111, Block A, Sector 64, Noida, Uttar Pradesh 201301<br>
                    Phone: +1 (555) 123-4567 | Email: info@yourcompany.com<br>
                    Website: <a href="https://www.yourcompany.com"
                        style="color:#3a86ff;text-decoration:none;">www.yourcompany.com</a><br>
                    GST: 22AAAAA0000A1Z5
                </div>
            </div>
            <div class="modern-invoice-title">
                <h2>INVOICE</h2>
                <div class="modern-status paid">
                    PAID
                </div>
            </div>
        </div>
        <!-- Meta -->
        <div class="modern-meta">
            <div class="modern-block">
                <h4>Invoice Details</h4>
                <div class="row"><span class="label">Invoice #</span><span
                        class="value">INV-001</span></div>
                <div class="row"><span class="label">Date</span><span
                        class="value">01 Jan 2024</span>
                </div>
                <div class="row"><span class="label">Due Date</span><span
                        class="value">31 Jan 2024</span>
                </div>
                <div class="row"><span class="label">Terms</span><span class="value">Net 30 Days</span></div>
            </div>
            <div class="modern-block">
                <h4>Bill To</h4>
                <div class="row"><span class="label">Company</span><span
                        class="value">Client Name</span></div>
                <div class="row"><span class="label">Contact</span><span
                        class="value">Contact Person</span></div>
                <div class="row"><span class="label">Email</span><span
                        class="value">email@client.com</span></div>
                <div class="row"><span class="label">Phone</span><span
                        class="value">+1 (555) 987-6543</span></div>
                <div class="row"><span class="label">Address</span><span
                        class="value">Client Address</span></div>
                <div class="row"><span class="label">GST</span><span class="value">22BBBBB0000B1Z6</span>
                </div>
            </div>
        </div>
        <!-- Items Table -->
        <table class="modern-items-table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Style Code</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>STY001</td>
                    <td class="text-center">10</td>
                    <td class="text-right">₹500.00</td>
                    <td class="text-right">₹5,000.00</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>STY002</td>
                    <td class="text-center">5</td>
                    <td class="text-right">₹1,000.00</td>
                    <td class="text-right">₹5,000.00</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>STY003</td>
                    <td class="text-center">2</td>
                    <td class="text-right">₹2,500.00</td>
                    <td class="text-right">₹5,000.00</td>
                </tr>
            </tbody>
        </table>
        <!-- Summary -->
        <div class="modern-summary">
            <table class="modern-summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="text-right">₹15,000.00</td>
                </tr>
                <tr>
                    <td class="label">GST (18%)</td>
                    <td class="text-right">₹2,700.00</td>
                </tr>
                <tr class="total-row">
                    <td>GRAND TOTAL</td>
                    <td class="text-right">₹17,700.00</td>
                </tr>
            </table>
        </div>
        <!-- Payment Info -->
        <div class="modern-payment">
            <h4>Payment Information</h4>
            <div class="modern-bank-details">
                <div>
                    <strong>Bank Name:</strong> Your Bank Name<br>
                    <strong>Account Name:</strong> Your Company Name<br>
                    <strong>Account Number:</strong> 1234567890
                </div>
                <div>
                    <strong>IFSC Code:</strong> BANK0001234<br>
                    <strong>Branch:</strong> Main Branch<br>
                    <strong>Account Type:</strong> Current Account
                </div>
            </div>
        </div>
        <!-- Terms -->
        <div class="modern-terms">
            <h4>Terms & Conditions</h4>
            <ul>
                <li>Payment is due within 30 days of invoice date</li>
                <li>Late payments may incur additional charges</li>
                <li>All disputes must be reported within 7 days of invoice receipt</li>
                <li>This invoice is computer generated and does not require physical signature</li>
                <li>Please quote invoice number in all communications</li>
            </ul>
        </div>
        <!-- Signature -->
        <div class="modern-signature-section">
            <div class="modern-signature-box">
                <div class="modern-signature-line">Client Signature</div>
            </div>
            <div class="modern-signature-box">
                <div class="modern-signature-line">Authorized Signatory</div>
            </div>
        </div>
        <!-- Footer -->
        <div class="modern-footer">
            Thank you for your business! For any questions regarding this invoice, please contact us at
            info@yourcompany.com
        </div>
    </div>
    <!-- Action Buttons -->
    <div id="modern-action-buttons" style="position: fixed; bottom: 32px; right: 32px; z-index: 9999;">
        <button onclick="downloadPDF()"
            style="padding: 16px 22px; background-color: #3a86ff; color: #fff; border: none; border-radius: 50%; box-shadow: 0 4px 16px rgba(58,134,255,0.18); cursor: pointer; font-size: 22px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
            title="Download PDF">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
                <path fill="#fff"
                    d="M12 16.5a1 1 0 0 1-1-1V5.91l-3.29 3.3a1 1 0 1 1-1.42-1.42l5-5a1 1 0 0 1 1.42 0l5 5a1 1 0 0 1-1.42 1.42L13 5.91V15.5a1 1 0 0 1-1 1Zm-7 3a1 1 0 0 1 0-2h14a1 1 0 1 1 0 2H5Z" />
            </svg>
        </button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const invoiceContent = document.querySelector('.modern-invoice-container');
            const actionButtons = document.getElementById('modern-action-buttons');
            actionButtons.style.display = 'none';

            html2canvas(invoiceContent, {
                scale: 2
            }).then(canvas => {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'Invoice-INV-001.png';
                link.click();
                actionButtons.style.display = 'flex';
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>

</html>
