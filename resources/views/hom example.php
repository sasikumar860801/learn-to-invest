
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include jQuery UI CSS for autocomplete -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
  

<body>
         <!-- customise  -->
         <div class="container">

         <div class="row">
            <div class="col-md-6">
            <!-- content start -->

            <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-info text-white">
                Stock Information
            </div>
            <div class="card-body">
                <form id="add-stock">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-3" style="margin-top: 20px";>
                                <label for="stockName" class="font-weight-bold">Search Stock name:</label>
                                <input type="text" class="form-control" id="stockName" required>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label for="stockName" class="font-weight-bold mt-md-4">Current Market Price:</label>
                                <span id="priceContainer" class="font-weight-bold text-success" style="font-size:20px;"></span>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label for="marketcap" class="font-weight-bold mt-md-4">Market Cap</label>
                                <div>
                                    <input type="radio" id="small" name="marketcap" value="small">
                                    <label for="small">Small</label><br>
                                    <input type="radio" id="medium" name="marketcap" value="medium">
                                    <label for="medium">Medium</label><br>
                                    <input type="radio" id="large" name="marketcap" value="large" checked>
                                    <label for="large">Large</label>
                                </div>
                            </div>
                           

                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-3" style="margin-top: 20px;">
                                <label for="quantity" class="font-weight-bold">Quantity</label>
                                <input type="text" class="form-control" id="quantity" required>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label for="stockName" class="font-weight-bold mt-md-4">Total Price:</label>
                                <span id="total_price" class="font-weight-bold text-success" style="font-size:20px;"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-right">
                <input type="button" id="addbtn" class="btn btn-info btn-sm" style="height:50px;" value="BUY stock">
            </div>
        </div>


    </div>
            <!-- content end -->
            </div>
            <div class="col-md-6" style="margin-top: 23px;">
                <!-- content start -->
                            <div class="container mt-4">
                            <div class="card">
            <div class="card-header bg-info text-white" >
                Investment Summary
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="totalInvest1" class="font-weight-bold">Total Invest:</label>
                        <span id="totalInvest1" class="font-weight-bold text-dark" style="font-size:20px;"></span>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="totalCurrentValue" class="font-weight-bold">Total Current Value:</label>
                        <span id="totalCurrentValue" class="font-weight-bold text-dark" style="font-size:20px;">{{ $totalCurrentValue }}</span>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">

                        <label for="totalPLAmount" class="font-weight-bold">PL Value</label>
                        <span id="plAmount" class="font-weight-bold" style="font-size:20px;"></span>

                        <label for="totalPLAmount" class="font-weight-bold"> / PL Percent</label>
                        <span id="total_pl_percent" class="font-weight-bold" style="font-size:20px;"></span>
                        
                        <span type="hidden" id="totalPLAmount" class="font-weight-bold" style="font-size:20px;">{{ $pl_amount }}</span>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="totalDayChangeValue" class="font-weight-bold">Day changes value:</label>
                        <span id="totalDayChangeValue" class="font-weight-bold" style="font-size:20px;"></span>


                        <label for="totalDayChangeValue" class="font-weight-bold">Day changes Percent:</label>
                        <span id="averageDayChangePercentage" class="font-weight-bold" style="font-size:20px;"></span>
                      

                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="XIRR" class="font-weight-bold">XIRR Returns:</label>
                        <span id="xirrPercentage" class="font-weight-bold" style="font-size:20px;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <!-- content end -->
            </div>
        </div>

         </div>
        <!-- customise end   -->
    
</body>

   
<!-- market cap based on index view -->



    </div>

</div>
<div class="col-md-06 market-cap-container" style="margin-left: 80px;">
        <label for="marketcap" class="market-cap-label" style="margin-left: 50px;" >Market Cap</label>
        <div class="radio-group">
            <div class="radio-container">
                <input type="radio" id="small" name="marketcaps" value="small">
                <label for="small" class="radio-label">Small</label>
            </div>
            <div class="radio-container">
                <input type="radio" id="medium" name="marketcaps" value="medium">
                <label for="medium" class="radio-label">Medium</label>
            </div>
            <div class="radio-container">
                <input type="radio" id="large" name="marketcaps" value="large" checked="checked">
                <label for="large" class="radio-label">Large</label>
            </div>
        </div>
    </div>




    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th class="sticky">Stock Name</th>
                                <th>Buy Date</th>
                                <th>Day Change</th>
                                <th>Total Price</th>
                                <th>P/L Amnt/perc</th>
                                <th>Exit</th>                
                            </tr>
                        </thead>
                        <tbody id="portfolioData">
                            @include('partials.portfolioData', ['data' => $data, 'data2' => $data2])
                        </tbody>
                        <tbody class="table-body4" id="table-body4">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
     <!-- exit form  -->
     <div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exit form</h5>
            </div>
            <div class="modal-body">
                <form style="display: flex; flex-direction: column;">
                    @csrf
                    <input type="hidden" id="hiddenStockId">
                    <p style="font-size: 16px; color: #333;">Stock name: <span id="stockName"></span></p>
                    <p style="font-size: 16px; color: #333;">Available Quantity: <span id="availableQuantity"></span></p>
                    <p style="font-size: 14px; color: #666;">Quantity:</p>
                    <input type="number" style="padding: 5px; border: 1px solid #ccc;" id="sellQuantity">
                    <button type="button" class="btn btn-danger" id="exit">Exit</button>
                </form>
            </div>
        </div>
    </div>

</div>


    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include jQuery UI for autocomplete -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
      $(document).ready(function(){
        $.ajax({
            url: '{{ route("filterByMarketCap") }}?marketcap=large',
            method: 'GET',
            success: function(response) {
                // Update the part of the page with the new data
                $('#portfolioData').html(response.html);
                
                // Update the total current value, total investment, and PL amount
                $('#totalCurrentValue').text(response.totalCurrentValue);
                $('#totalInvest1').text(response.totalInvest);
                $('#plAmount').text(response.plAmount);
                $('#totalDayChangeValue').text(response.totalDayChangeValue);
                $('#averageDayChangePercentage').text(response.averageDayChangePercentage + '%');
                $('#total_pl_percent').text(response.total_pl_percent + '%');

                
            },
          
       
        });

       //make color for positive and negative values 
        var plAmountElement = document.getElementById('totalPLAmount');
            var plAmount = parseFloat(plAmountElement.textContent);

            if (plAmount > 0) {
                plAmountElement.style.color = 'green';
            } else if (plAmount < 0) {
                plAmountElement.style.color = 'red';
            }

           

        $('#stockTable tbody tr').each(function() {
            var stockId = $(this).find('td:first').text(); // Get the stock ID from the first column
            callApi(stockId); // Call the API for each stock ID
        });


        var selectedValue; // Variable to store the selected value

        // Initialize autocomplete
        $('#stockName').autocomplete({
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("search") }}',
                    dataType: 'json',
                    data: {
                        q: request.term,
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                // Fill the input field with the selected label
                $('#stockName').val(ui.item.label);
                // Store the selected value
                selectedValue = ui.item.value;
                $('#priceContainer').empty();
                // Call the API here
                callApi(selectedValue);

                return false; // Prevent the default behavior of the autocomplete widget
            }
        });
        function callApi(selectedValue) {
    var apiUrl = '{{ route("fetch.chart", ["id" => ":id"]) }}';
    apiUrl = apiUrl.replace(':id', selectedValue);

    $.ajax({
        url: apiUrl,
        dataType: 'json',
        success: function(response) {
            var today = new Date().toISOString().split('T')[0];
            var todayPrice = null;
            var latestPrice = null;

            // Find the dataset for "Price"
            var priceDataset = response.datasets.find(function(dataset) {
                return dataset.metric === "Price";
            });

            if (priceDataset) {
                // Check for today's price first
                var todayValue = priceDataset.values.find(function(value) {
                    return value[0] === today;
                });

                if (todayValue) {
                    todayPrice = todayValue[1];
                } else {
                    // If today's price is not available, get the latest price available
                    latestPrice = priceDataset.values[priceDataset.values.length - 1][1];
                }
            }

            // Display the price value
            var priceContainer = document.getElementById('priceContainer');
            if (todayPrice !== null) {
                priceContainer.textContent = todayPrice;
            } else if (latestPrice !== null) {
                priceContainer.textContent = latestPrice;
            } else {
                console.log('No price data available');
            }

            
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}




        function calculateTotalPrice(quantity, pricePerUnit) {
            return quantity * pricePerUnit;
        }

        // Event listener for quantity change
        $('#quantity').on('input', function() {
            var quantity = parseFloat($(this).val());
            var pricePerUnit = parseFloat($('#priceContainer').text());
            var totalPrice = calculateTotalPrice(quantity, pricePerUnit);

            // Display the total price
            $('#total_price').text(totalPrice.toFixed(2)); // Assuming you want to display the price with two decimal places
        });

        $('#addbtn').click(function(e){
    e.preventDefault();
    // Gather data from form fields
    var stockId = selectedValue; // Assuming selectedValue holds the stock ID
    var stockName = $('#stockName').val();
    // var marketcap=$('#marketcap').val();
    var marketcap = document.querySelector('input[name="marketcap"]:checked').value;

    var quantity = $('#quantity').val();
    var buyPrice = $('#priceContainer').text();
    var totalPrice = $('#total_price').text();
    var buyDate = new Date().toISOString(); // Current date/time

    // Prepare data object to send to server
    var data = {
        stock_id: stockId,
        stock_name: stockName, // Make sure stockName is properly escaped
        Market_cap:marketcap,
        quantity: quantity,
        buy_price: buyPrice,
        total_price: totalPrice,
        buy_date: buyDate // Include buy_date in the data object
    };

    // Send data to server using AJAX POST request
    $.ajax({
        type: "POST",
        url: '{{ route("store") }}',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert("test");
            $('#stockName').val("");
            $('#quantity').val("");


            $('#priceContainer, #stockName, #quantity, #total_price').empty();
            
            $.ajax({
            url: '{{ route("filterByMarketCap") }}?marketcap=large',
            method: 'GET',
            success: function(response) {
                // Update the part of the page with the new data
                $('#portfolioData').html(response.html);
                
                // Update the total current value, total investment, and PL amount
                $('#totalCurrentValue').text(response.totalCurrentValue);
                $('#totalInvest1').text(response.totalInvest);
                $('#plAmount').text(response.plAmount);
                $('#totalDayChangeValue').text(response.totalDayChangeValue);
                $('#averageDayChangePercentage').text(response.averageDayChangePercentage + '%');
                
            },
          
       
        });
            },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('An error occurred while storing data');
        }
    });
  });
  $('#buy_more').click(function(e){
    e.preventDefault();
    var id=$(this).data('stock_id');

  });

      //exit form data 
      $(document).on('click', '.exit-button', function() {
    // Get the current row
    var currentRow = $(this).closest('tr');
    
    // Get the stock details from the current row
    var stockId = currentRow.find('.stock-id').text();
    var stockName = currentRow.find('.stock-name').text();
    var stockQuantity = currentRow.find('.stock-quantity').text().split(' ')[0]; // Extract the numeric quantity
    
    // Populate form fields with dynamic data
    $('#hiddenStockId').val(stockId); // Set the hidden stock ID
    $('#sellQuantity').val(stockQuantity); // Set the sell quantity input to available quantity
    $('#stockName').text(stockName);
    $('#availableQuantity').text(stockQuantity + ' Shares'); // Re-add the 'Shares' text
});

// exit call ajax
$('#exit').click(function() {
    var stockId = $('#hiddenStockId').val();
    var sellQuantity = $('#sellQuantity').val();
    var currentMarketPrice = $('input.latestPrice[data-stock-id="' + stockId + '"]').val();

    $.ajax({
        url: '{{ route("exitstock") }}',
        method: 'POST',
        data: {
            stock_id: stockId,
            quantity: sellQuantity,
            current_market_price: currentMarketPrice, // Ensure this matches the backend parameter name
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                location.reload(); // Reload the page to reflect changes
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.responseText);
        }
    });
});

// index view based on marketcap

$('input[name="marketcaps"]').change(function() {
        var selectedMarketCap = $(this).val();

        $.ajax({
            url: '{{ route("filterByMarketCap") }}', // Make sure this route exists
            method: 'GET',
            data: {
                marketcap: selectedMarketCap
            },
            success: function(response) {
                // Update the part of the page with the new data
                $('#portfolioData').html(response.html);
                
                // Update the total current value, total investment, and PL amount
                $('#totalCurrentValue').text(response.totalCurrentValue);
                $('#totalInvest1').text(response.totalInvest);
                $('#plAmount').text(response.plAmount);
                $('#totalDayChangeValue').text(response.totalDayChangeValue);
                $('#averageDayChangePercentage').text(response.averageDayChangePercentage + '%');
                $('#total_pl_percent').text(response.total_pl_percent + '%');
                $('#xirrPercentage').text(response.xirrPercentage + '%');

                // Ensure values are parsed as floats
                var plAmount = parseFloat(response.plAmount);
                var total_pl_percent = parseFloat(response.total_pl_percent);
                var totalDayChangeValue = parseFloat(response.totalDayChangeValue);
                var averageDayChangePercentage = parseFloat(response.averageDayChangePercentage);

                // Apply color based on values
                $('#plAmount').css('color', plAmount > 0 ? 'green' : 'red');
                $('#total_pl_percent').css('color', total_pl_percent > 0 ? 'green' : 'red');
                $('#totalDayChangeValue').css('color', totalDayChangeValue > 0 ? 'green' : 'red');
                $('#averageDayChangePercentage').css('color', averageDayChangePercentage > 0 ? 'green' : 'red');
                $('#xirrPercentage').css('color', xirrPercentage > 0 ? 'green' : 'red');

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
