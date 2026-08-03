<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get supplier ID from URL
$supplier_id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if supplier exists
$query = "SELECT * FROM suppliers WHERE id = '$supplier_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_suppliers.php');
    exit;
}

// Fetch supplier data
$supplier_data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">Edit Supplier</h2>
        <form id="edit-supplier-form">
            <div class="mt-4">
                <label for="name" class="block text-gray-700 text-sm font-bold">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $supplier_data['name']; ?>" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded">
            </div>
            <div class="mt-4">
                <label for="email" class="block text-gray-700 text-sm font-bold">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $supplier_data['email']; ?>" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded">
            </div>
            <div class="mt-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $supplier_data['phone']; ?>" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded">
            </div>
            <div class="mt-4">
                <label for="address" class="block text-gray-700 text-sm font-bold">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded"><?php echo $supplier_data['address']; ?></textarea>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded hover:bg-indigo-700">Update Supplier</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-supplier-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/suppliers.php', {
                method: 'PUT',
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = 'list_suppliers.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch((error) => console.error(error));
        });
    </script>
</body>
</html>