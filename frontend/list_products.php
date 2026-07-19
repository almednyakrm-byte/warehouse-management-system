<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-700 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Index</a>
            <span>Welcome, <?= $current_user ?></span>
            <a href="logout.php" class="text-blue-500 hover:text-blue-700">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl text-gray-700 mb-4">Products List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_products.php">Add New Item</a>
            </button>
            <input type="text" id="search" class="py-2 px-4 border border-gray-300 rounded" placeholder="Search...">
        </div>
        <table id="products-table" class="w-full text-gray-700">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="products-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get products list
        fetch('../backend/products.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('products-tbody');
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${product.id}</td>
                        <td class="px-4 py-2">${product.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_products.php?id=${product.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-blue-500 hover:text-blue-700" onclick="deleteProduct(${product.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete product using AJAX
        function deleteProduct(id) {
            fetch('../backend/products.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`#products-tbody tr:nth-child(${id})`);
                    row.remove();
                } else {
                    console.error('Error deleting product:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toUpperCase();
            const rows = document.querySelectorAll('#products-tbody tr');
            rows.forEach(row => {
                const nameCell = row.cells[1];
                if (nameCell.textContent.toUpperCase().indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>