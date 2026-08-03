**create_المخزون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    if (!empty($name) && !empty($quantity) && !empty($unit_price)) {
        // Insert data into database
        $query = "INSERT INTO المخزون (name, quantity, unit_price) VALUES ('$name', '$quantity', '$unit_price')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_المخزون.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500 mb-4">Create المخزون</h2>
        <form id="create-form" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter name">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter quantity">
            </div>
            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price:</label>
                <input type="number" id="unit_price" name="unit_price" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter unit price">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/المخزون.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'Error inserting data') {
                            alert('Error inserting data');
                        } else if (response === 'Please fill in all fields') {
                            alert('Please fill in all fields');
                        } else {
                            window.location.href = 'list_المخزون.php';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/المخزون.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    if (!empty($name) && !empty($quantity) && !empty($unit_price)) {
        // Insert data into database
        $query = "INSERT INTO المخزون (name, quantity, unit_price) VALUES ('$name', '$quantity', '$unit_price')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Return success message
            echo 'Success';
        } else {
            // Return error message
            echo 'Error inserting data';
        }
    } else {
        // Return error message
        echo 'Please fill in all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>