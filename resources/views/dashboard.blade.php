@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

    body::after {
    content: '';
    display: block;
    height: 100px;
    background-color: #1e1e1e;
}


html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    background-color: #121212 !important;
    color: #ffffff !important;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

body > .main-content {
    flex: 1;
    background-color: #1e1e1e;
}

/* .main-content {
    flex: 1;
    background-color: #1e1e1e !important;
    display: flex;
    flex-direction: column;
} */
    .main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #1e1e1e !important;
    min-height: 100vh;
}

    .bg-gray-50,
    .bg-white {
        background-color: #1e1e1e !important;
    }

    .text-gray-800,
    .text-gray-700,
    .text-gray-600,
    .text-gray-500,
    .text-gray-900 {
        color: #ffffff !important;
    }

    .border-gray-300,
    .border-gray-200 {
        border-color: #444 !important;
    }

    input,
    textarea,
    select,
    .form-control {
        background-color: #2c2c2c !important;
        color: #fff !important;
        border-color: #555 !important;
    }

    input::placeholder,
    textarea::placeholder {
        color: #aaa !important;
    }

    .table {
        color: #fff;
    }

    .table thead {
        background-color: #222;
    }

    .table tbody tr {
        background-color: #1a1a1a;
    }

    .table-bordered {
        border-color: #444;
    }

    .modal-content {
        background-color: #1e1e1e !important;
        color: #fff !important;
    }

    .stock-item {
        background-color: #2c2c2c;
        color: #fff !important;
    }

    .stock-item:hover {
        background-color: #444 !important;
        color: #fff !important;
    }

    #stock-results {
        background-color: #2c2c2c !important;
        color: #fff !important;
        border-color: #444 !important;
    }


</style>

<div class="main-content">
    <!-- All dashboard content here -->
     <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-2xl shadow-xl p-6 sm:p-8 bg-gray-50">

        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-white">Welcome, {{ Auth::user()->name }} 👋</h1>
            <p class="text-white mt-2">Manage your stock portfolio easily.</p>
        </div>

        <div class="mb-6 text-center">
            <div class="text-sm text-white uppercase tracking-wide">Available Balance</div>
            <div class="text-2xl font-semibold text-green-600 mt-1">₹ <span id="available_balance">{{ number_format($users_data->available_balance, 2) }}</span></div>
        </div>

        <!-- Stock Search -->
        <div class="relative">
            <label for="stock-search" class="block font-medium text-white mb-1">Search Stock</label>
            <input type="text" id="stock-search" placeholder="Type to search..." autocomplete="off"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm" />
            <div id="stock-results"
                class="absolute z-50 mt-1 w-full border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto"></div>
        </div>

        <!-- Selected Stock -->
        <div id="stock-section" class="mt-6 hidden transition-all duration-200 ease-in-out">
            <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                <div class="mb-4">
                    <span class="text-sm text-white">Market Price:</span>
                    <span id="market-price" class="text-green-600 font-semibold text-white">₹</span>
                </div>

                <label for="quantity" class="block text-sm font-medium text-white mb-1">Enter Quantity</label>
                <input type="number" id="quantity"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400"
                    placeholder="e.g. 5" />

                <p class="mt-3 text-white text-base">Total Price: ₹<span id="total-price" class="font-bold text-green-600">0.00</span></p>

                <button id="buy-button"
                    class="mt-4 w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    Buy Now
                </button>
            </div>
        </div>
    </div>
</div>

<br>

@if($users_portfolio_data->count())
<div class="table-responsive">
    <table class="table table-bordered table-dark table-hover text-center align-middle">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left">S-No</th>
                <th class="px-6 py-3 text-left">Stock</th>
                <th class="px-6 py-3 text-center">Qty</th>
                <th class="px-6 py-3 text-center">Buy Date</th>
                <th class="px-6 py-3 text-center">Buy Price</th>
                <th class="px-6 py-3 text-center">Total Invested</th>
                <th class="px-6 py-3 text-center">Current Value</th>
                <th class="px-6 py-3 text-center">P/L</th>
                <th class="px-6 py-3 text-center">P/L %</th>
                <th class="px-6 py-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users_portfolio_data as $portfolio)
                @php
                    $latest_price = $portfolio->latest_price ?? 0;
                    $current_value = $latest_price * $portfolio->quantity;
                    $pl_amount = $current_value - $portfolio->total_price;
                    $pl_percent = $portfolio->total_price > 0 ? ($pl_amount / $portfolio->total_price) * 100 : 0;
                    $pl_color = $pl_amount >= 0 ? 'text-success' : 'text-danger';
                    $avgArray = json_decode($portfolio->avg ?? '[]', true);
                @endphp
                <tr class="text-center">
                    <td class="px-6 py-4 text-left font-medium text-white">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-left font-medium text-white">
                        @if(count($avgArray) > 1)
                            <a href="#" class="text-primary view-avg-link" data-avg='@json($avgArray)' data-stock="{{ $portfolio->stock_name }}">
                                {{ $portfolio->stock_name }}
                            </a>
                        @else
                            {{ $portfolio->stock_name }}
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $portfolio->quantity }}</td>
                    <td class="px-6 py-4">{{ date('d M Y', strtotime($portfolio->buy_date)) }}</td>
                    <td class="px-6 py-4">₹{{ number_format($portfolio->buy_price, 2) }}</td>
                    <td class="px-6 py-4">₹{{ number_format($portfolio->total_price, 2) }}</td>
                    <td class="px-6 py-4">₹{{ number_format($current_value, 2) }}</td>
                    <td class="px-6 py-4 {{ $pl_color }}">₹{{ number_format($pl_amount, 2) }}</td>
                    <td class="px-6 py-4 {{ $pl_color }}">{{ number_format($pl_percent, 2) }}%</td>
                    <td class="px-6 py-4">
                        <button class="btn btn-sm btn-danger exit-stock-btn"
                            data-stock-id="{{ $portfolio->stock_id }}"
                            data-stock-name="{{ $portfolio->stock_name }}"
                            data-quantity="{{ $portfolio->quantity }}">
                            Exit Stock
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="mt-6">
    <div>
        {{ $users_portfolio_data->links() }}
    </div>
</div>

</div>

<!-- Exit Stock Modal -->
<div class="modal fade" id="exitStockModal" tabindex="-1" aria-labelledby="exitStockLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="exitStockLabel">Exit Stock</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="exitStockForm">
          <input type="hidden" id="exitStockId" name="stock_id" />
          <div class="mb-3">
            <label for="exitQuantity" class="form-label">Quantity to Exit</label>
            <input type="number" class="form-control" id="exitQuantity" name="quantity" required min="1">
            <div class="form-text text-white" id="maxQtyHint"></div>
          </div>
          <button type="submit" class="btn btn-danger">Confirm Exit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Buy History Modal -->
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


<div style="flex-grow: 1; background-color: #1e1e1e;"></div>

@endsection


@section('scripts')
<!-- CSS -->

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let selectedStockId = null;
let selectedStockName = '';
let latestPrice = 0;

// Autocomplete search
$('#stock-search').on('input', function () {
    let query = $(this).val();

    if (query.length < 2) {
        $('#stock-results').empty().hide();
        return;
    }

    $.getJSON("/search", { q: query }, function (data) {
        let resultsBox = $('#stock-results');
        resultsBox.empty();

        if (data.length > 0) {
            data.forEach(item => {
                resultsBox.append(`<div class="stock-item cursor-pointer px-4 py-2 hover:bg-blue-50 text-gray-800" data-id="${item.value}" data-name="${item.label}">${item.label}</div>`);
            });
            resultsBox.show();
        } else {
            resultsBox.hide();
        }
    });
});

// Handle click on stock item
$(document).on('click', '.stock-item', function () {
    selectedStockId = $(this).data('id');
    selectedStockName = $(this).data('name');

    $('#stock-search').val(selectedStockName);
    $('#stock-results').hide();

    $.get(`/fetch-chart/${selectedStockId}`, function (data) {
        let priceDataset = data.datasets.find(ds => ds.metric === 'Price');
        let lastPriceEntry = priceDataset.values[priceDataset.values.length - 1];
        latestPrice = parseFloat(lastPriceEntry[1]);

        $('#selected-stock-name').text(selectedStockName);
        $('#market-price').text(latestPrice.toFixed(2));
        $('#stock-section').removeClass('hidden');
        $('#quantity').val('');
        $('#total-price').text('0.00');
        $('#buy-button').prop('disabled', true);
    });
});

// Quantity input
$('#quantity').on('input', function () {
    let qty = parseInt($(this).val());
    if (!isNaN(qty) && latestPrice > 0) {
        let total = qty * latestPrice;
        $('#total-price').text(total.toFixed(2));
        $('#buy-button').prop('disabled', false);
          $('#buy-button').addClass('bg-blue-500');
    } else {
        $('#total-price').text('0.00');
        $('#buy-button').prop('disabled', true);
    }
});

// Buy action
$('#buy-button').on('click', function () {
    if(confirm("Are you sure you want to buy this stock?")){
        
    let qty = parseInt($('#quantity').val());
    let total = qty * latestPrice;

    if (!selectedStockId || !qty || total <= 0) {
        alert("Please select a stock and enter a valid quantity.");
        return;
    }

    $.ajax({
        url: "/users/buy-stock",
        method: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            stock_id: selectedStockId,
            stock_name: selectedStockName,
            quantity: qty,
            buy_price: latestPrice
        },
        success: function (response) {
            alert(response.message);
            location.reload();
        },
        error: function (xhr) {
            alert(xhr.responseJSON.error);
        }
    });
}

});
</script>

<script>
$(document).ready(function () {
    let maxQuantity = 0;

    // Show modal and fill stock data
    $('.exit-stock-btn').on('click', function () {
        const stockId = $(this).data('stock-id');
        const stockQty = $(this).data('quantity');
        const stockName = $(this).data('stock-name');

        maxQuantity = stockQty;

        $('#exitStockId').val(stockId);
        $('#exitQuantity').val('').attr('max', stockQty);
        $('#maxQtyHint').text(`You own ${stockQty} shares of ${stockName}.`);

        $('#exitStockModal').modal('show');
    });

    // Handle form submission
    $('#exitStockForm').on('submit', function (e) {
        e.preventDefault();

        const stockId = $('#exitStockId').val();
        const quantity = parseFloat($('#exitQuantity').val());

        if (quantity > maxQuantity) {
            alert('Entered quantity exceeds your holdings.');
            return;
        }

        $.ajax({
            url: '{{ route('users_exitstock') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                stock_id: stockId,
                quantity: quantity
            },
            success: function (response) {
                alert(response.message);
                location.reload(); // Refresh page to reflect portfolio changes
            },
            error: function (xhr) {
                const error = xhr.responseJSON?.error || 'Something went wrong.';
                alert('Error: ' + error);
            }
        });
    });
});
</script>

<script>

    $(document).ready(function () {
    $(document).on('click', '.view-avg-link', function (e) {
        e.preventDefault();

        const avgData = $(this).data('avg');
        const stockName = $(this).data('stock');

        let html = `<h6 class="mb-3">Stock: <strong>${stockName}</strong></h6>`;
        html += `<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Buy Price</th>
                            <th>Quantity</th>
                            <th>Buy Date</th>
                        </tr>
                    </thead>
                    <tbody>`;

        avgData.forEach(entry => {
            html += `<tr>
                        <td>₹${parseFloat(entry.buy_price).toFixed(2)}</td>
                        <td>${entry.quantity}</td>
                        <td>${new Date(entry.buy_date).toLocaleString()}</td>
                     </tr>`;
        });

        html += `</tbody></table>`;

        $('#avgHistoryContent').html(html);

        const modal = new bootstrap.Modal(document.getElementById('avgHistoryModal'));
        modal.show();
    });
});

</script>
@endsection
