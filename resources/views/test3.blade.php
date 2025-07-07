<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Purchases</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <style>
        .product-img {
    height: 40px;
}

.product-link {
    color: #000;
    text-decoration: none;
}

.product-link:hover {
    color: #007bff;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-error {
    background-color: #dc3545 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.text-muted {
    color: #6c757d !important;
}

.text-success {
    color: #28a745 !important;
}

.text-error {
    color: #dc3545 !important;
}

    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-uppercase mb-3">Products Purchases</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Item Details</th>
                                <th class="text-right">Sold</th>
                                <th>Gain</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be populated here -->
                            <!-- Example Row -->
                            <tr>
                                <td><img src="path/to/image" alt="Product Image" class="product-img"></td>
                                <td>
                                    <a href="#!" class="product-link">Product Name</a>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="status-indicator bg-success"></span>
                                        <small class="text-muted ml-2">In stock</small>
                                    </div>
                                </td>
                                <td class="text-right text-muted">3,345</td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> 33.34%
                                    </span>
                                    from last week
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-link">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Repeat for other rows -->
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-sm btn-outline-secondary mt-3">
                    <i class="fas fa-arrow-down"></i> View All Products
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const productsData = [
            { id: 1, productImg: 'path/to/img1.jpg', name: 'Product 1' },
            { id: 2, productImg: 'path/to/img2.jpg', name: 'Product 2' },
            { id: 3, productImg: 'path/to/img3.jpg', name: 'Product 3' },
            { id: 4, productImg: 'path/to/img4.jpg', name: 'Product 4' },
            { id: 5, productImg: 'path/to/img5.jpg', name: 'Product 5' }
        ];

        const PURCHASES_DATA = [
            { product: productsData[0], sold: '3,345', stock: { title: '20 remaining', status: 'error' }, gain: 33.34 },
            { product: productsData[1], sold: '720', stock: { title: 'In stock', status: 'success' }, gain: -21.2 },
            { product: productsData[2], sold: '1,445', stock: { title: 'In stock', status: 'success' }, gain: 23.34 },
            { product: productsData[3], sold: '2,500', stock: { title: '45 remaining', status: 'warning' }, gain: 28.78 },
            { product: productsData[4], sold: '223', stock: { title: 'Paused', status: '' }, gain: -18.18 }
        ];

        const STATUS_CONFIG = {
            success: 'bg-success',
            error: 'bg-error',
            warning: 'bg-warning'
        };

        const tableBody = document.querySelector('tbody');
        PURCHASES_DATA.forEach(purchase => {
            const { product, sold, stock, gain } = purchase;
            const gainClass = gain >= 0 ? 'text-success' : 'text-error';
            const gainIcon = gain >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

            const row = `
                <tr>
                    <td><img src="${product.productImg}" alt="Product Image" class="product-img"></td>
                    <td>
                        <a href="#!" class="product-link">${product.name}</a>
                        <div class="d-flex align-items-center mt-1">
                            <span class="status-indicator ${STATUS_CONFIG[stock.status] || 'bg-secondary'}"></span>
                            <small class="text-muted ml-2">${stock.title}</small>
                        </div>
                    </td>
                    <td class="text-right text-muted">${sold}</td>
                    <td>
                        <span class="${gainClass}">
                            <i class="fas ${gainIcon}"></i> ${gain}%
                        </span>
                        from last week
                    </td>
                    <td class="text-right">
                        <button class="btn btn-sm btn-link">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    });
</script>

</html>
