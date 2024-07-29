<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
{{-- order
user_data --}}
        <h1>Invoice #{{ $order->id }}</h1>
        <p>Date: {{ $order->created_at }}</p>
        <p>Customer: {{ $user_data->name }}</p>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product_data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->current_price }}</td>
                        <td>{{ $order->total_amount}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Total: {{ $order->total_amount }}</h2>
    </div>
</body>
</html>
