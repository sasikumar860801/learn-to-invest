
<!DOCTYPE html>
<html>
<head>
    <title>PL Report</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        #heading {
            color: blue;
            margin-right: 500px;
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
            transition: all 0.3s;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #343a40;
        }
        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #47748b;
        }
        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }
        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
        }
        #sidebar ul li a:hover {
            color: #7386d5;
            background: #fff;
        }
        #sidebar.active {
            margin-left: -250px;
        }
        #content {
            width: 100%;
            padding: 20px;
        }
        .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 40px;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        .navbar-btn {
            box-shadow: none;
            outline: none !important;
            border: none;
        }
        .navbar h2 {
            margin: 0;
        }
    </style>
</head>
<body>

<div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Menu</h3>
            </div>
            <ul class="list-unstyled components">
              <li >
                    <a href="home">Home</a>
                </li>
         
                <li class="active">
                    <a href="pl_report">PL Report</a>
                </li>

                  <li>
                    <a href="admin/user_list">User List</a>
                </li>

                 <li>
                    <a href="admin/recent_buy">Recent Buy</a>
                </li>

                 <li>
                    <a href="admin/recent_sell">Recent Sell</a>
                </li>
           
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span></span>
                    </button>
                    <h2 class="ml-4" id="heading">PL Report</h2>
                </div>
            </nav>
            <div class="content">
            <!-- @yield('content') -->
            


            <div class="container mt-4">
    <h2 class="mb-4">PL Report</h2>

    <div class="form-group">
        <label style="font-weight: bold;">Market Cap:</label>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <button type="button" name="marketcap" id="all" value="all" class="btn btn-outline-primary">All</button>
            <button type="button" name="marketcap" id="large" value="large" class="btn btn-outline-primary">Large</button>
            <button type="button" name="marketcap" id="medium" value="medium" class="btn btn-outline-primary">Medium</button>
            <button type="button" name="marketcap" id="small" value="small" class="btn btn-outline-primary">Small</button>
            <button type="button" name="marketcap" id="risky" value="risky" class="btn btn-outline-primary">Risky</button>
        </div>
    </div>
</div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>S.No</th>
                    <th>Stock Name</th>
                    <th>Buy Date</th>
                    <th>Sell Date</th>
                    <th>Quantity</th>
                    <th>Total Buy Price</th>
                    <th>Total Sell Price</th>
                    <th>Realized PL Amount</th>
                    <th>Realized PL Percentage</th>
                </tr>
            </thead>
            <tbody id="plReportTableBody">
                @foreach($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->stock_name }}</td>
                        <td>{{ $item->buy_date }}</td>
                        <td>{{ $item->sell_date }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->total_buy_price }}</td>
                        <td>{{ $item->total_sell_price }}</td>
                        <td>{{ $item->realized_pl_amount }}</td>
                        <td>{{ $item->realized_pl_percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>

            </div>
        </div>
    </div>





<script>
$(document).ready(function() {

    $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });

            $('button[name="marketcap"]').click(function () {
        var selectedMarketCap = $(this).val();

        $.ajax({
            url: '{{ route("pl_report") }}',
            method: 'GET',
            data: { Market_cap: selectedMarketCap },
            success: function(response) {
                var tableBody = $('#plReportTableBody');
                tableBody.empty();

                response.data.forEach(function(item, index) {
                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + item.stock_name + '</td>' +
                        '<td>' + item.buy_date + '</td>' +
                        '<td>' + item.sell_date + '</td>' +
                        '<td>' + item.quantity + '</td>' +
                        '<td>' + item.total_buy_price + '</td>' +
                        '<td>' + item.total_sell_price + '</td>' +
                        '<td style="color: ' + (item.realized_pl_amount > 0 ? 'green' : 'red') + '">' + item.realized_pl_amount + '</td>' +
                        '<td style="color: ' + (item.realized_pl_amount > 0 ? 'green' : 'red') + '">' + item.realized_pl_percentage + '%</td>' +
                    '</tr>';

                    tableBody.append(row);
                });
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
});
</script>
</body>
</html>
