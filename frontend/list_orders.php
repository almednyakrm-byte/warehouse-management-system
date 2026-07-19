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
    <title>Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-700 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Index</a>
            <span>Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-blue-500 hover:text-blue-700">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl text-gray-700 mb-4">Orders List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_orders.php" class="text-white">Add New Item</a>
            </button>
            <input type="text" id="search" class="bg-gray-200 py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="orders-table" class="w-full table-auto bg-white shadow-md rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Order Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="orders-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch orders data from backend
        fetch('../backend/orders.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('orders-tbody');
                data.forEach(order => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${order.id}</td>
                        <td class="px-4 py-2">${order.order_name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_orders.php?id=${order.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-blue-500 hover:text-blue-700" onclick="deleteOrder(${order.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete order using AJAX
        function deleteOrder(id) {
            fetch('../backend/orders.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const rows = document.getElementById('orders-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('orders-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const id = row.children[0].textContent;
                const orderName = row.children[1].textContent;
                if (id.toString().includes(searchValue) || orderName.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>