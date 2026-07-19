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
    <title>Warehouses</title>
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
        <h1 class="text-2xl text-gray-700 mb-4">Warehouses</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_warehouses.php">Add New Item</a>
            </button>
            <input type="search" id="search" class="px-4 py-2 border border-gray-400 rounded" placeholder="Search...">
        </div>
        <table id="warehouse-table" class="w-full table-auto border border-gray-400">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="warehouse-table-body">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch warehouses data from backend
        fetch('../backend/warehouses.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('warehouse-table-body');
                data.forEach(warehouse => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${warehouse.id}</td>
                        <td class="px-4 py-2">${warehouse.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_warehouses.php?id=${warehouse.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-blue-500 hover:text-blue-700" onclick="deleteWarehouse(${warehouse.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete warehouse via AJAX
        function deleteWarehouse(id) {
            fetch('../backend/warehouses.php', {
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
                    const tableRows = document.getElementById('warehouse-table-body').children;
                    for (let i = 0; i < tableRows.length; i++) {
                        if (tableRows[i].children[0].textContent == id) {
                            tableRows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting warehouse:', data.error);
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const tableRows = document.getElementById('warehouse-table-body').children;
            for (let i = 0; i < tableRows.length; i++) {
                const rowText = tableRows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    tableRows[i].style.display = '';
                } else {
                    tableRows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>