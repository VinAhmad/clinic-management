<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .clinic-info {
            text-align: right;
        }
        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            color: #2e4053;
        }
        .invoice-details {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .invoice-id {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2e4053;
        }
        .patient-info, .doctor-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #2e4053;
        }
        .totals {
            margin-top: 30px;
            text-align: right;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #2e4053;
            margin-top: 10px;
        }
        .payment-info {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-print {
            background-color: #3498db;
            color: white;
        }
        .btn-download {
            background-color: #2ecc71;
            color: white;
        }
        .btn-back {
            background-color: #95a5a6;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        @media print {
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div>
                <h1>Health Clinic</h1>
            </div>
            <div class="clinic-info">
                <div class="clinic-name">Health Clinic Services</div>
                <p>
                    123 Medical Center Blvd<br>
                    Healthcare City, HC 12345<br>
                    Phone: (123) 456-7890<br>
                    Email: info@healthclinic.com
                </p>
            </div>
        </div>
        
        <div class="invoice-details">
            <div class="invoice-id">INVOICE #{{ $payment->id }}</div>
            <p>
                <strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}<br>
                <strong>Payment Status:</strong> 
                @if($payment->status == 'paid' || $payment->status == 'Completed')
                    <span style="color: green;">Paid</span>
                @else
                    <span style="color: orange;">{{ ucfirst($payment->status) }}</span>
                @endif
            </p>
        </div>
        
        <div class="row">
            <div class="col-md-6 patient-info">
                <h3>Billed To:</h3>
                <p>
                    <strong>{{ $payment->patient->name }}</strong><br>
                    {{ $payment->patient->email }}<br>
                    {{ $payment->patient->address ?? 'No address provided' }}<br>
                    Phone: {{ $payment->patient->phone ?? 'No phone provided' }}
                </p>
            </div>
            
            <div class="col-md-6 doctor-info">
                <h3>Service Provider:</h3>
                <p>
                    <strong>Dr. {{ $payment->doctor->name }}</strong><br>
                    {{ $payment->doctor->specialization ?? 'General Practitioner' }}<br>
                    {{ $payment->doctor->email }}
                </p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if($payment->appointment)
                            Medical Consultation with Dr. {{ $payment->doctor->name }}
                        @else
                            Medical Services
                        @endif
                    </td>
                    <td>
                        @if($payment->appointment)
                            {{ \Carbon\Carbon::parse($payment->appointment->appointment_date)->format('F d, Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($payment->created_at)->format('F d, Y') }}
                        @endif
                    </td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals">
            <p><strong>Subtotal:</strong> ${{ number_format($payment->amount, 2) }}</p>
            <p><strong>Tax:</strong> $0.00</p>
            <div class="total-amount">Total: ${{ number_format($payment->amount, 2) }}</div>
        </div>
        
        <div class="payment-info">
            <h3>Payment Information:</h3>
            <p>
                <strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}<br>
                <strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}<br>
                <strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') ?? 'N/A' }}
            </p>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing Health Clinic Services. This invoice was generated on {{ date('F d, Y') }}.</p>
            <p>For any questions regarding this invoice, please contact our billing department.</p>
        </div>
        
        <div class="action-buttons">
            <button class="btn btn-print" onclick="window.print()">Print Invoice</button>
            <a href="{{ route('payments.view-invoice', ['payment' => $payment->id, 'download' => 'pdf']) }}" class="btn btn-download">Download PDF</a>
            <a href="{{ route('payments.index', $payment->id) }}" class="btn btn-back">Back to Payment</a>
        </div>
    </div>
</body>
</html>
