<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-indigo-500 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
        <h1 class="text-2xl font-bold mb-4">Products List</h1>
        <input type="text" id="search" placeholder="Search products" class="w-full p-2 mb-4 border border-gray-300 rounded">
        <table id="products-table" class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-300">
                <tr>
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="products-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
        <a href="create_products.php" class="bg-indigo-500 text-white p-2 mt-4 rounded">Add New Item</a>
    </main>

    <script>
        // Fetch products data from backend
        fetch('../backend/products.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('products-tbody');
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="p-2">${product.id}</td>
                        <td class="p-2">${product.name}</td>
                        <td class="p-2">
                            <a href="edit_products.php?id=${product.id}" class="text-indigo-500">Edit</a>
                            <button class="text-red-500" onclick="deleteProduct(${product.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete product via AJAX
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
                    // Remove the deleted product from the table
                    const rows = document.getElementById('products-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                }
            });
        }

        // Search products in real-time
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('products-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>