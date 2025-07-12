@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    html, body {
        height: 100%;
        margin: 0;
        background-color: #121212 !important;
        color: #ffffff !important;
    }

    .container {
        min-height: 100vh;
        background-color: #1e1e1e !important;
    }

    .table-dark thead th {
        background-color: #222 !important;
        color: #fff !important;
    }

    .table-bordered {
        border-color: #444 !important;
    }

    .table tbody tr {
        background-color: #1a1a1a !important;
    }

    .modal-content {
        background-color: #1e1e1e !important;
        color: #fff !important;
    }

    .modal-header {
        border-bottom: 1px solid #444;
    }

    .btn-close-white {
        filter: invert(1);
    }

    .text-black {
        color: #fff !important;
    }
</style>

<div class="container py-5">
    <h1 class="mb-4 text-white">P&L Report</h1>

    @if($data->count())
        <div class="table-responsive">
            <table class="table table-dark table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Stock Name</th>
                        <th>Qty</th>
                        <th>Buy Price</th>
                        <th>Sell Price</th>
                        <th>Total Buy</th>
                        <th>Total Sell</th>
                        <th>P/L</th>
                        <th>P/L %</th>
                        <th>Buy Date</th>
                        <th>Sell Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        @php
                            $avgArray = json_decode($row->avg ?? '[]', true);
                            $pl = $row->total_sell_price - $row->total_buy_price;
                            $plPercent = $row->total_buy_price > 0 ? ($pl / $row->total_buy_price) * 100 : 0;
                            $plColor = $pl >= 0 ? 'text-success' : 'text-danger';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if(count($avgArray) > 1)
                                    <a href="#"
                                       class="text-info view-avg-link fw-bold"
                                       data-avg='@json($avgArray)'
                                       data-stock="{{ $row->stock_name }}">
                                       {{ $row->stock_name }}
                                    </a>
                                @else
                                    {{ $row->stock_name }}
                                @endif
                            </td>
                            <td>{{ $row->quantity }}</td>
                            <td>₹{{ number_format($row->buy_price, 2) }}</td>
                            <td>₹{{ number_format($row->sell_price, 2) }}</td>
                            <td>₹{{ number_format($row->total_buy_price, 2) }}</td>
                            <td>₹{{ number_format($row->total_sell_price, 2) }}</td>
                            <td class="{{ $plColor }}">₹{{ number_format($pl, 2) }}</td>
                            <td class="{{ $plColor }}">{{ number_format($plPercent, 2) }}%</td>
                            <td>{{ \Carbon\Carbon::parse($row->buy_date)->format('d M Y, h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->sell_date)->format('d M Y, h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-white">No P&L data available.</p>
    @endif
</div>

<!-- Modal for Avg Details -->
<div class="modal fade" id="avgHistoryModal" tabindex="-1" aria-labelledby="avgHistoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="avgHistoryLabel">Buy History</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="avgHistoryContent"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.view-avg-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const avg = JSON.parse(this.dataset.avg);
            const stock = this.dataset.stock;

            let html = `<h5 class="mb-3">Stock: <span class="text-info">${stock}</span></h5>`;
            html += `<table class="table table-bordered table-dark table-sm">
                        <thead>
                            <tr>
                                <th>Buy Price</th>
                                <th>Quantity</th>
                                <th>Buy Date</th>
                            </tr>
                        </thead>
                        <tbody>`;

            avg.forEach(item => {
                const date = new Date(item.buy_date);
                const formattedDate = date.toLocaleString();
                html += `<tr>
                            <td>₹${parseFloat(item.buy_price).toFixed(2)}</td>
                            <td>${item.quantity}</td>
                            <td>${formattedDate}</td>
                        </tr>`;
            });

            html += `</tbody></table>`;

            document.getElementById('avgHistoryContent').innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById('avgHistoryModal'));
            modal.show();
        });
    });
});
</script>
@endsection
