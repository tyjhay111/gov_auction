<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $payment->reference }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .receipt-box { border: 1px solid #ddd; padding: 30px; max-width: 600px; margin: 0 auto; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .total { font-weight: bold; font-size: 1.2em; border-top: 2px solid #eee; padding-top: 10px; margin-top: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="header">
            <h2>Government Auction Platform</h2>
            <p>Official Receipt</p>
        </div>
        
        <div class="row">
            <span><strong>Date:</strong></span>
            <span>{{ $payment->created_at->format('M d, Y H:i:s') }}</span>
        </div>
        <div class="row">
            <span><strong>Reference:</strong></span>
            <span>{{ $payment->reference }}</span>
        </div>
        <div class="row">
            <span><strong>Billed To:</strong></span>
            <span>{{ $payment->user->name }} ({{ $payment->user->email }})</span>
        </div>

        <br><br>
        
        <div class="row">
            <span><strong>Item Description</strong></span>
            <span><strong>Amount</strong></span>
        </div>
        <div class="row">
            <span>Auction Win: {{ $payment->auction->title }} (ID: {{ $payment->auction->id }})</span>
            <span>${{ number_format($payment->amount, 2) }}</span>
        </div>

        <div class="row total">
            <span>Total Paid</span>
            <span>${{ number_format($payment->amount, 2) }}</span>
        </div>

        <div style="text-align: center; margin-top: 40px;" class="no-print">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print / Save as PDF</button>
            <a href="{{ route('my-bids') }}" style="margin-left: 10px; color: blue; text-decoration: none;">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>
