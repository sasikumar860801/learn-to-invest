<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PL Amount</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<body>
    <span id="totalPLAmount" style="font-weight: bold; font-size: 20px;">
        {{ $pl_amount }}
    </span><br>

    <script>
        $(document).ready(function() {
            var plAmountElement = document.getElementById('totalPLAmount');
            var plAmount = parseFloat(plAmountElement.textContent);

            if (plAmount > 0) {
                plAmountElement.style.color = 'green';
            } else if (plAmount < 0) {
                plAmountElement.style.color = 'red';
            }
        });
    </script>
</body>
</html>
