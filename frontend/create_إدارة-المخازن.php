**create_إدارة-المخازن.php**

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

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description)) {
        // Insert data into database
        $query = "INSERT INTO إدارة_المخازن (name, description) VALUES ('$name', '$description')";
        $result = mysqli_query($conn, $query);

        // Check if data has been inserted successfully
        if ($result) {
            // Redirect back to list page
            header('Location: list_إدارة-المخازن.php');
            exit;
        } else {
            // Display error message
            $error = 'Error inserting data';
        }
    } else {
        // Display error message
        $error = 'Please fill in all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إدارة مخازن جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a !important;
        }
        .text-indigo-500 {
            color: #6b7280 !important;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">إضافة إدارة مخازن جديدة</h2>
        <form id="create-form" method="post" class="mt-4">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الإدارة</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-2"><?= $error ?></p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/إدارة-المخازن.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_إدارة-المخازن.php';
                        } else {
                            alert('Error creating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إدارة-المخازن.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $query = "INSERT INTO إدارة_المخازن (name, description) VALUES ('".$_POST['name']."', '".$_POST['description']."')";
    $result = mysqli_query($conn, $query);

    // Check if data has been inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating record';
    }
}

// Close database connection
mysqli_close($conn);
?>