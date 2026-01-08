<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Montserrat:wght@300;400;600&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        /* RESET & BASE */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            background: #f3f4f6; 
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        /* KERTAS STRUK */
        .receipt {
            background: #fff;
            width: 350px; 
            padding: 40px 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            position: relative;
        }

        /* HEADER BRANDING */
        .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand h1 {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            letter-spacing: 5px;
            font-weight: 700;
            color: #111;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .brand p {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #666;
        }

        /* INFO SECTION */
        .info-group {
            margin-bottom: 25px;
            text-align: center;
        }
        .customer-name {
            font-family: 'Great Vibes', cursive;
            font-size: 26px; /* Dibesarkan sedikit biar elegan */
            color: #333;
            margin: 5px 0;
        }
        .meta-info {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* ITEMS TABLE */
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 11px;
            color: #333;
        }
        .item-name {
            flex: 1;
            padding-right: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .item-qty {
            color: #888;
            margin-right: 10px;
            font-size: 10px;
        }
        .item-price {
            font-weight: 600;
        }

        /* TOTALS SECTION */
        .divider {
            height: 1px;
            background: #111;
            margin: 20px 0;
            opacity: 0.1;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 6px;
            color: #555;
        }
        .grand-total {
            font-size: 14px;
            font-weight: 700;
            color: #000;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #888;
            line-height: 1.6;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .barcode {
            margin-top: 20px;
            height: 30px;
            background-image: linear-gradient(to right, black 0%, black 10%, white 10%, white 20%, black 20%, black 30%, white 30%, white 40%, black 40%, black 70%, white 70%, white 80%, black 80%, black 100%);
            width: 100%;
            opacity: 0.7;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #888;
            font-size: 11px;
            letter-spacing: 1px;
            transition: color 0.3s;
        }
        .btn-back:hover { color: #000; }

        @media print {
            body { background: white; padding: 0; display: block; }
            .receipt { width: 100%; box-shadow: none; padding: 0; }
            .btn-back { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt">
        <div class="brand">
            <h1>{{ $setting->shop_name }}</h1>
            <p>{{ $setting->shop_slogan }}</p>
            <p style="font-size: 8px; margin-top: 5px; color:#999">{{ $setting->shop_address }}</p>
        </div>

        <div class="info-group">
            <p style="font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px;">Exclusively Served For</p>
            <div class="customer-name">{{ $transaction->customer_name ?? 'Valued Guest' }}</div>
        </div>

        <div class="meta-info">
            <span>INV: {{ substr($transaction->invoice_code, -8) }}</span>
            <span>{{ $transaction->created_at->format('d.m.Y • H:i') }}</span>
        </div>

        @foreach($transaction->details as $item)
        <div class="item-row">
            <span class="item-qty">{{ $item->qty }}x</span>
            <span class="item-name">{{ $item->product->name }}</span>
            <span class="item-price">{{ number_format($item->subtotal) }}</span>
        </div>
        @endforeach

        <div class="divider"></div>

        <div class="total-row">
            <span>Subtotal</span>
            <span>{{ number_format($transaction->total_price) }}</span>
        </div>
        
        @if($transaction->tax > 0)
        <div class="total-row">
            <span>Tax (11%)</span>
            <span>+ {{ number_format($transaction->tax) }}</span>
        </div>
        @endif

        @if($transaction->discount > 0)
        <div class="total-row">
            <span>Privilege Disc.</span>
            <span>- {{ number_format($transaction->discount) }}</span>
        </div>
        @endif

        <div class="total-row grand-total">
            <span>TOTAL DUE</span>
            <span>IDR {{ number_format($transaction->grand_total) }}</span>
        </div>

        <div class="total-row" style="margin-top: 10px;">
            <span>Cash Presented</span>
            <span>{{ number_format($transaction->cash_amount) }}</span>
        </div>
        <div class="total-row">
            <span>Change Due</span>
            <span>{{ number_format($transaction->change_amount) }}</span>
        </div>

        <div class="footer">
            <p>Merci Beaucoup</p>
            <p style="font-size: 7px; margin-top: 5px;">No Refund / Exchange without Receipt</p>
            <p style="margin-top: 10px;">www.{{ str_replace(' ', '', strtolower($setting->shop_name)) }}.com</p>
            <div class="barcode"></div>
        </div>
        
        <a href="{{ route('pos.index') }}" class="btn-back">← RETURN TO BOUTIQUE</a>
    </div>

</body>
</html>