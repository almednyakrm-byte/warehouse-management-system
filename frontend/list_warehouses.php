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
<body>
    <header class="bg-indigo-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="bg-gray-300 hover:bg-gray-400 text-indigo-500 font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Warehouses</h1>
        <div class="flex justify-between mb-4">
            <a href="create_warehouses.php" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="search" id="search" class="px-4 py-2 border border-gray-300 rounded" placeholder="Search...">
        </div>
        <table id="warehouse-table" class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-300">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="warehouse-table-body">
                <!-- Table content will be generated dynamically -->
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
                            <a href="edit_warehouses.php?id=${warehouse.id}" class="text-indigo-500 hover:text-indigo-600">Edit</a>
                            <button class="text-red-500 hover:text-red-600 ml-4" onclick="deleteWarehouse(${warehouse.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete warehouse
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
                    // Remove deleted row from table
                    const rows = document.getElementById('warehouse-table-body').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
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
            const rows = document.getElementById('warehouse-table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const id = row.children[0].textContent;
                const name = row.children[1].textContent;
                if (id.toString().includes(searchValue) || name.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>