<?php
// create_suppliers.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
$mod_slug = 'suppliers';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-indigo-500">Create Supplier</h2>
        <form id="create-supplier-form">
            <div class="mt-4">
                <label for="name" class="block text-gray-300">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <label for="email" class="block text-gray-300">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <label for="phone" class="block text-gray-300">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <label for="address" class="block text-gray-300">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="w-full mt-4 p-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">Create Supplier</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-supplier-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/suppliers.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>