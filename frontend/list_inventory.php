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
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-700 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Index</a>
            <span>Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-blue-500 hover:text-blue-700">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl text-gray-700 mb-4">Inventory List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_inventory.php" class="text-white">Add New Item</a>
            </button>
            <input type="text" id="search" class="bg-gray-200 p-2 rounded" placeholder="Search...">
        </div>
        <table id="inventory-table" class="w-full text-gray-700">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get inventory list
        fetch('../backend/inventory.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${item.id}</td>
                        <td class="px-4 py-2">${item.name}</td>
                        <td class="px-4 py-2">${item.description}</td>
                        <td class="px-4 py-2">
                            <a href="edit_inventory.php?id=${item.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-blue-500 hover:text-blue-700" onclick="deleteItem(${item.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete item via AJAX
        function deleteItem(id) {
            fetch('../backend/inventory.php', {
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
                    const tableBody = document.getElementById('table-body');
                    const rows = tableBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            tableBody.removeChild(rows[i]);
                            break;
                        }
                    }
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>