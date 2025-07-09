<!-- resources/views/recent_sell.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Recent Sell Report</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
        }
        #sidebar .sidebar-header {
            padding: 20px;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 10px;
            display: block;
            color: #fff;
        }
        #sidebar ul li a:hover {
            color: #7386d5;
            background: #fff;
        }
        #content {
            width: 100%;
            padding: 20px;
        }
        .navbar {
            padding: 15px;
            background: #fff;
            margin-bottom: 20px;
            box-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
    </style>
    <div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Menu</h3>
        </div>
        <ul class="list-unstyled components">
            <li><a href="{{ url('home') }}">Home</a></li>
            <li><a href="{{ url('pl_report') }}">PL Report</a></li>
            <li><a href="{{ url('admin/user_list') }}">User List</a></li>
            <li><a href="{{ url('admin/recent_buy') }}">Recent Buy</a></li>
            <li><a href="{{ url('admin/recent_sell') }}">Recent Sell</a></li>
        </ul>
    </nav>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4 text-primary">Recent Sold Stocks</h2>

    <div class="table-responsive shadow-sm">
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>S.No</th>
                    <th>User Name</th>
                    <th>Stock Name</th>
                    <th>Qty</th>
                    <th>Buy Price</th>
                    <th>Sell Price</th>
                    <th>Total Buy</th>
                    <th>Total Sell</th>
                    <th>Buy Date</th>
                    <th>Sell Date</th>
                    <th>P/L</th>
                    <th>P/L %</th>
                </tr>
            </thead>
            <tbody>
                @php $modals = ''; @endphp

                @foreach($data as $index => $item)
                    @php
                        $avgArray = json_decode($item->avg, true);
                        $showAvgModal = is_array($avgArray) && count($avgArray) > 1;

                        $pl = (float)$item->total_sell_price - (float)$item->total_buy_price;
                        $plPercent = ((float)$item->total_buy_price > 0)
                            ? ($pl / (float)$item->total_buy_price) * 100 : 0;
                    @endphp

                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->user_name }}</td>

                        <td>
                            @if($showAvgModal)
                                <a href="javascript:void(0)" class="text-primary font-weight-bold showAvgModal" data-id="{{ $item->id }}">
                                    {{ $item->stock_name }}
                                </a>
                            @else
                                <span>{{ $item->stock_name }}</span>
                            @endif
                        </td>

                        <td>{{ $item->quantity }}</td>
                        <td>₹ {{ number_format((float)$item->buy_price, 2) }}</td>
                        <td>₹ {{ number_format((float)$item->sell_price, 2) }}</td>
                        <td>₹ {{ number_format((float)$item->total_buy_price, 2) }}</td>
                        <td>₹ {{ number_format((float)$item->total_sell_price, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->buy_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->sell_date)->format('d M Y') }}</td>

                        <td class="{{ $pl >= 0 ? 'text-success' : 'text-danger' }}">
                            ₹ {{ number_format($pl, 2) }}
                        </td>
                        <td class="{{ $plPercent >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($plPercent, 2) }}%
                        </td>
                    </tr>

                    {{-- Buffer modal if needed --}}
                    @if($showAvgModal)
                        @php ob_start(); @endphp
                        <div class="modal fade" id="avgModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="avgModalLabel{{ $item->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="avgModalLabel{{ $item->id }}">
                                    Avg Details — {{ $item->stock_name }}
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    &times;
                                </button>
                              </div>
                              <div class="modal-body">
                                <table class="table table-sm table-bordered">
                                  <thead>
                                    <tr>
                                      <th>Buy Price</th>
                                      <th>Buy Date</th>
                                      <th>Quantity</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($avgArray as $avg)
                                    <tr>
                                      <td>₹ {{ $avg['buy_price'] }}</td>
                                      <td>{{ \Carbon\Carbon::parse($avg['buy_date'])->format('d M Y') }}</td>
                                      <td>{{ $avg['quantity'] }}</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        @php $modals .= ob_get_clean(); @endphp
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>

{{-- Place modals after the table --}}
{!! $modals !!}

<script>
$(function() {
    $('.showAvgModal').on('click', function() {
        $('#avgModal' + $(this).data('id')).modal('show');
    });
});
</script>
</body>
</html>
