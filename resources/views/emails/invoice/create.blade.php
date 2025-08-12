{{-- new invoice created --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Created</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f7fa;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <!-- Header with Logo -->
        <tr>
            <td style="background-color: #ffffff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                <img src="https://crm.vastramveda.com/media/logos/Group20.png" alt="VV CRM" style="width: 150px; height: auto; margin-bottom: 15px; border-radius: 4px;">
                <h1 style="color: #464646; margin: 0; font-size: 24px;">Invoice Created</h1>
            </td>
        </tr>

        <!-- Greeting -->
        <tr>
            <td style="padding: 30px 30px 20px;">
                <p style="margin: 0; font-size: 16px; line-height: 24px; color: #333;">Dear {{ $data['user']->name }},</p>
                <p style="margin: 10px 0; font-size: 16px; line-height: 24px; color: #333;">
                    A new invoice has been created for you. Please find the details below:
                </p>
            </td>
        </tr>

        <!-- Invoice Summary -->
        <tr>
            <td style="padding: 0 30px;">
                <h2 style="color: #0e4c83; font-size: 20px; margin: 0 0 20px;">Invoice Details:</h2>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666;">Invoice Number:</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #333;">{{ $data['invoice']->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666;">Amount:</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #333;">{{ $data['invoice']->amount }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666;">Date:</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #333;">{{ $data['invoice']->created_at->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666;">Download Invoice:</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #333;">
                            <a href="{{ url('/admin/invoice/generate-invoice/' . $data['invoice']->id) }}" style="color: #0e4c83; text-decoration: underline;">Download PDF</a>
                        </td>
                </table>
            </td>
        </tr>

        <!-- Note Section -->
        <tr>
            <td style="padding: 0 30px 20px;">
                <p style="margin: 0; font-size: 14px; color: #666; font-style: italic;">
                    <strong>Please Note:</strong> To check the invoice details, please click the link below:
                </p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding-bottom: 20px;">
                <a href="{{ url('/invoices/' . $data['invoice']->id) }}" style="display: inline-block; padding: 12px 30px; background-color: #0e4c83; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: 500; word-spacing: 2px;">View Invoice</a>
            </td>
        </tr>

        <!-- Contact Section -->
        <tr>
            <td style="padding: 30px; background-color: #f8f9fa; border-radius: 0 0 8px 8px; border: 0.2px solid #dedddd;">
                <p style="margin: 0 0 10px; font-size: 15px; color: #666;">Best Regards,</p>
                <p style="margin: 0 0 20px; font-size: 15px; color: #666;">{{ config('app.name') }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
