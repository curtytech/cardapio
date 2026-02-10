<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupom #{{ $sell->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 10px;
            padding: 10px;
            width: 80mm; /* Standard thermal printer width */
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .info {
            margin-bottom: 10px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items th, .items td {
            text-align: left;
            /* padding: 4px 0; */
            padding: 4px 50px;
        }
        .items .qty {
            width: 10%;
            text-align: center;
        }
        .items .price {
            text-align: right;
            width: 25%;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
            padding-right: 50px;

        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            @page {
                margin: 0;
                size: auto;
            }
            body {
                width: 100%;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <strong>{{ $sell->user->name ?? 'Restaurante' }}</strong><br>
        PEDIDO #{{ $sell->id }}<br>
        {{ $sell->date ? $sell->date->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
    </div>

    <div class="info">
        @if($sell->client_name)
            Cliente: {{ $sell->client_name }}<br>
        @endif
        @if($sell->table_id)
            Mesa: {{ $sell->restaurantTable->number ?? $sell->table_id }}<br>
        @endif
        @if($sell->status)
            Status: {{ $sell->status }}<br>
        @endif
    </div>

    <table class="items">
        <thead>
            <tr>
                <th class="qty">Qtd</th>
                <th>Item</th>
                <th class="price">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sell->sellProductsGroups as $item)
                <tr>
                    <td class="qty">{{ $item->quantity }}</td>
                    <td>{{ $item->product->name ?? 'Item removido' }}</td>
                    <td class="price">R$ {{ number_format($item->quantity * ($item->product->sell_price ?? 0), 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL: R$ {{ number_format($sell->total, 2, ',', '.') }}
    </div>

    @if($sell->observation)
    <div class="info" style="margin-top: 10px; border-top: 1px dotted #ccc; padding-top: 5px;">
        <strong>Obs:</strong> {{ $sell->observation }}
    </div>
    @endif

    <div class="footer">
        Obrigado pela preferência!<br>
        Volte sempre.
    </div>
</body>
</html>