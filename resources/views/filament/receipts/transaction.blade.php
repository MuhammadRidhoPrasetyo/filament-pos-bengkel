<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Struk - {{ $transaction->number }}</title>
    <style>
        /* Thermal receipt friendly styles (narrow width) */
        body {
            font-family: "Arial", sans-serif;
            font-size: 12px;
            color: #000;
        }

        .receipt {
            width: 280px;
            margin: 0 auto;
            padding: 8px;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 11px;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items td {
            padding: 2px 0;
        }

        .total {
            font-weight: bold;
        }

        .muted {
            color: #666;
            font-size: 11px;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="center">
            <div style="font-weight:bold; font-size:14px">{{ $transaction->store?->name ?? 'Toko' }}</div>
            <div class="muted small">{{ $transaction->store?->address ?? '' }}</div>
            <div class="muted small">Tel: {{ $transaction->store?->phone ?? '' }}</div>
            <hr />
        </div>

        <div class="small">
            <div>Struk: <strong>{{ $transaction->number }}</strong></div>
            <div>Tanggal: {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}</div>
            <div>Kasir: {{ $transaction->cashier?->name ?? '-' }}</div>
            <div>Customer: {{ $transaction->customer?->name ?? '-' }}</div>
        </div>

        <hr />

        <table class="items small">
            @foreach ($transaction->items as $item)
                @php
                    $name = Str::limit($item->product?->full_name ?? ($item->description ?? '-'), 40);
                    $qty = (int) $item->quantity;
                    $unitPrice = (float) ($item->unit_price ?? 0);
                    $finalUnit = (float) ($item->final_unit_price ?? $unitPrice);
                    $lineSubtotal = (float) ($item->line_subtotal ?? $qty * $unitPrice);
                    $lineDiscount = (float) ($item->item_discount_amount ?? $lineSubtotal - $qty * $finalUnit);
                    $lineTotal = (float) ($item->line_total ?? $qty * $finalUnit);
                @endphp

                <tr>
                    <td style="width:60%">{{ $name }}</td>
                    <td style="width:10%" class="center">x{{ $qty }}</td>
                    <td style="width:30%" class="right">Rp {{ number_format($lineTotal, 0, ',', '.') }}</td>
                </tr>

                <tr>
                    <td colspan="3" class="small muted">
                        Harga/unit: Rp {{ number_format($unitPrice, 0, ',', '.') }}
                        &nbsp;•&nbsp; Setelah diskon/unit: Rp {{ number_format($finalUnit, 0, ',', '.') }}
                        @if ($lineDiscount > 0)
                            &nbsp;•&nbsp; Diskon item: Rp {{ number_format($lineDiscount, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        <hr />

        <div style="display:flex; justify-content:space-between;">
            <div class="small">Subtotal (sebelum diskon item)</div>
            <div class="small">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
        </div>

        <div style="display:flex; justify-content:space-between;">
            <div class="small">Total Diskon Item</div>
            <div class="small">− Rp {{ number_format($transaction->item_discount_total ?? 0, 0, ',', '.') }}</div>
        </div>

        <div style="display:flex; justify-content:space-between;">
            <div class="small">Setelah Diskon Item</div>
            <div class="small">Rp
                {{ number_format($transaction->subtotal_after_item_discount ?? $transaction->subtotal - ($transaction->item_discount_total ?? 0), 0, ',', '.') }}
            </div>
        </div>

        @if (!empty($transaction->universal_discount_amount) || !empty($transaction->universal_discount_mode))
            <div style="display:flex; justify-content:space-between;">
                <div class="small">Diskon Universal
                    @if (
                        !empty($transaction->universal_discount_mode) &&
                            $transaction->universal_discount_mode === 'percent' &&
                            !empty($transaction->universal_discount_value))
                        ({{ $transaction->universal_discount_value }}%)
                    @endif
                </div>
                <div class="small">− Rp {{ number_format($transaction->universal_discount_amount ?? 0, 0, ',', '.') }}
                </div>
            </div>
        @endif

        <div style="display:flex; justify-content:space-between;" class="total">
            <div>Grand Total</div>
            <div>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</div>
        </div>

        <div style="display:flex; justify-content:space-between;" class="small">
            <div>Dibayar</div>
            <div>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</div>
        </div>

        <div style="display:flex; justify-content:space-between;" class="small">
            <div>Kembalian</div>
            <div>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</div>
        </div>

        <hr />

        <div class="center small muted">Terima kasih. Selamat datang kembali!</div>
        <div style="height:20px"></div>
    </div>
</body>

</html>

@if (request()->boolean('print'))
    <script>
        // Wait a moment to allow fonts/media to load, then trigger print
        window.addEventListener('load', function() {
            setTimeout(function() {
                try {
                    window.print();
                } catch (e) {
                    console.warn('Print failed', e);
                }
            }, 250);
        });
    </script>
@endif
