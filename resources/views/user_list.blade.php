
<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
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
                    <!-- <h2 class="ml-4" id="heading">User List</h2> -->
                </div>
            </nav>
            <div class="content">
            <!-- @yield('content') -->
            


            <div class="container mt-4">
    <!-- <h2 class="mb-4">User List</h2> -->

</div>

    <div class="container mt-4">
    <h2 class="mb-4 text-primary">User List</h2>

    <div class="table-responsive shadow-sm">
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Joining Date</th>
                    <th>Available Balance</th>
                    <th>Total Invest</th>
                    <th>Current Value</th>
                    <th>Total Amount</th>
                    <th>PL %</th>

                </tr>
            </thead>
            <tbody>
              @forelse($data as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <a href="{{ route('user_list_detail', ['id' => $item['id']]) }}" class="text-primary font-weight-bold">
                {{ $item['name'] }}
            </a>
        </td>
       <td class="text-right">{{ \Carbon\Carbon::parse($item['created_at'])->format('d M Y') }}</td>

        <td class="text-right">₹ {{ $item['available_balance'] }}</td>
      
        <td class="text-right">₹ {{ $item['total_invest'] }}</td>
        <td class="text-right">₹ {{ $item['current_value'] }}</td>
        <td class="text-right font-weight-bold text-success">₹ {{ $item['total_amount'] }}</td>
<td class="text-right font-weight-bold {{ $item['pl_perc'] > 0 ? 'text-success' : 'text-danger' }}">₹ {{ $item['pl_perc'] }}</td>

    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted">No users found.</td>
    </tr>
@endforelse

            </tbody>
        </table>
        <div class="mt-3">
    {{ $data->links() }}
</div>

    </div>
</div>
 




</div>

            </div>
        </div>
    </div>





<script>

</script>
</body>
</html>
