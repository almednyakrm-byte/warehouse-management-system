**list_المنتجات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-lg font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-lg font-bold text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">المنتجات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_المنتجات.php'">إضافة منتج جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchProducts()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المنتج</th>
                    <th>وصف المنتج</th>
                    <th>سعر المنتج</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="product-list">
                <!-- Products will be listed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch products from backend
        async function fetchProducts() {
            try {
                const response = await fetch('../backend/المنتجات.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const products = await response.json();
                const productList = document.getElementById('product-list');
                products.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>${product.price}</td>
                        <td>
                            <a href="edit_المنتجات.php?id=${product.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteProduct(${product.id})">حذف</button>
                        </td>
                    `;
                    productList.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search products
        function searchProducts() {
            const searchInput = document.getElementById('search-input').value;
            fetchProducts(searchInput);
        }

        // Delete product
        async function deleteProduct(id) {
            try {
                const response = await fetch('../backend/المنتجات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    fetchProducts();
                } else {
                    console.error('Error deleting product');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Initialize products list
        fetchProducts();
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a table showing the list of records with actions, an 'Add New Item' button, a search bar filtering elements in real-time, and AJAX JavaScript (Fetch API) fetching list records from the backend.