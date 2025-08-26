<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Manejo de Productos</h1>
        
        <!-- Add Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Agregar Nuevo Producto</h5>
            </div>
            <div class="card-body">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Precio</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="productPrice" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </form>
            </div>
        </div>
        
        <!-- Edit Product Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm">
                            <input type="hidden" id="editProductId">
                            <div class="mb-3">
                                <label for="editProductName" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="editProductName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductPrice" class="form-label">Precio</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="editProductPrice" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="saveEditBtn">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products List -->
        <div class="card">
            <div class="card-header">
                <h5>Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <!-- Products will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // DOM Elements
        const addProductForm = document.getElementById('addProductForm');
        const editProductForm = document.getElementById('editProductForm');
        const productsTableBody = document.getElementById('productsTableBody');
        const saveEditBtn = document.getElementById('saveEditBtn');
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        
        // Load all products when page loads
        document.addEventListener('DOMContentLoaded', fetchProducts);
        
        // Add new product
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('productName').value;
            const price = document.getElementById('productPrice').value;
            
            fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    price: parseFloat(price)
                })
            })
            .then(response => response.json())
            .then(data => {
                fetchProducts();
                addProductForm.reset();
            })
            .catch(error => console.error('Error adding product:', error));
        });
        
        // Edit product
        saveEditBtn.addEventListener('click', function() {
            const id = document.getElementById('editProductId').value;
            const name = document.getElementById('editProductName').value;
            const price = document.getElementById('editProductPrice').value;
            
            fetch(`/api/products/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    price: parseFloat(price)
                })
            })
            .then(response => response.json())
            .then(data => {
                fetchProducts();
                editModal.hide();
            })
            .catch(error => console.error('Error updating product:', error));
        });
        
        // Fetch all products
        function fetchProducts() {
            fetch('/api/products')
                .then(response => response.json())
                .then(products => {
                    productsTableBody.innerHTML = '';
                    
                    products.forEach(product => {
                        const row = document.createElement('tr');
                        
                        row.innerHTML = `
                            <td>${product.id}</td>
                            <td>${product.name}</td>
                            <td>$${parseFloat(product.price).toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="${product.id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${product.id}">Delete</button>
                            </td>
                        `;
                        
                        productsTableBody.appendChild(row);
                    });
                    
                    // Add event listeners to edit and delete buttons
                    document.querySelectorAll('.edit-btn').forEach(button => {
                        button.addEventListener('click', openEditModal);
                    });
                    
                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', deleteProduct);
                    });
                })
                .catch(error => console.error('Error fetching products:', error));
        }
        
        // Open edit modal and populate form
        function openEditModal() {
            const id = this.getAttribute('data-id');
            
            fetch(`/api/products/${id}`)
                .then(response => response.json())
                .then(product => {
                    document.getElementById('editProductId').value = product.id;
                    document.getElementById('editProductName').value = product.name;
                    document.getElementById('editProductPrice').value = product.price;
                    
                    editModal.show();
                })
                .catch(error => console.error('Error fetching product details:', error));
        }
        
        // Delete product
        function deleteProduct() {
            if (confirm('Are you sure you want to delete this product?')) {
                const id = this.getAttribute('data-id');
                
                fetch(`/api/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(() => {
                    fetchProducts();
                })
                .catch(error => console.error('Error deleting product:', error));
            }
        }
    </script>
</body>
</html>
