<!DOCTYPE html>
<html>
<head>
    <title>AJAX CRUD in Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Products</h2>
        
        <!-- Add Product Form -->
        <div class="card mb-4">
            <div class="card-header">Add New Product</div>
            <div class="card-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <h1>i am anil developer</h1>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </form>
            </div>
        </div>
        
        <!-- Products Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productsTable">
                <!-- Products will be loaded here via AJAX -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // CSRF token setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Load products on page load
            loadProducts();

            // Form submission for create/update
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                
                const productId = $('#productId').val();
                const url = productId ? `/products/${productId}` : '/products';
                const method = productId ? 'PUT' : 'POST';
                
                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: $('#name').val(),
                        description: $('#description').val(),
                        price: $('#price').val()
                    },
                    success: function(response) {
                        $('#productForm').trigger('reset');
                        $('#productId').val('');
                        $('#saveBtn').text('Save');
                        loadProducts();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Function to load all products
            function loadProducts() {
                $.get('/products', function(data) {
                    let rows = '';
                    data.forEach(product => {
                        rows += `
                            <tr id="row${product.id}">
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.description || 'N/A'}</td>
                                <td>$${product.price.toFixed(2)}</td>
                                <td>
                                    <button onclick="editProduct(${product.id})" class="btn btn-sm btn-warning">Edit</button>
                                    <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#productsTable').html(rows);
                });
            }

            // Edit product
            window.editProduct = function(id) {
                $.get(`/products/${id}`, function(product) {
                    $('#productId').val(product.id);
                    $('#name').val(product.name);
                    $('#description').val(product.description);
                    $('#price').val(product.price);
                    $('#saveBtn').text('Update');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                });
            };

            // Delete product
            window.deleteProduct = function(id) {
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: `/products/${id}`,
                        method: 'DELETE',
                        success: function() {
                            loadProducts();
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            };
        });
    </script>
</body>
</html>