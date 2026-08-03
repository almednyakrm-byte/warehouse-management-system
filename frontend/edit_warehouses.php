<?php
// edit_warehouses.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_warehouses.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Warehouse</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">Edit Warehouse</h2>
        <form id="edit-warehouse-form">
            <div class="mt-4">
                <label for="name" class="block text-gray-300">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="address" class="block text-gray-300">Address:</label>
                <input type="text" id="address" name="address" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="city" class="block text-gray-300">City:</label>
                <input type="text" id="city" name="city" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="state" class="block text-gray-300">State:</label>
                <input type="text" id="state" name="state" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="zip" class="block text-gray-300">Zip:</label>
                <input type="text" id="zip" name="zip" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700">Update</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-warehouse-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/warehouses.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('address').value = data.address;
                document.getElementById('city').value = data.city;
                document.getElementById('state').value = data.state;
                document.getElementById('zip').value = data.zip;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/warehouses.php`, {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_warehouses.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>