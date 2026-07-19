**create_متابة-الحوادث.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($date) || empty($time) || empty($location)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO متابة_الحوادث (name, description, date, time, location) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $description, $date, $time, $location);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_متابة-الحوادث.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900">Create New متابة الحوادث</h1>
    <form action="" method="post" class="mt-6 space-y-6">
        <div class="space-y-4 rounded-md shadow-sm -space-y-1">
            <div>
                <label for="name" class="sr-only">Name</label>
                <input type="text" name="name" id="name" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Name">
            </div>
            <div>
                <label for="description" class="sr-only">Description</label>
                <textarea name="description" id="description" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Description"></textarea>
            </div>
            <div class="flex justify-between">
                <div>
                    <label for="date" class="sr-only">Date</label>
                    <input type="date" name="date" id="date" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Date">
                </div>
                <div>
                    <label for="time" class="sr-only">Time</label>
                    <input type="time" name="time" id="time" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Time">
                </div>
            </div>
            <div>
                <label for="location" class="sr-only">Location</label>
                <input type="text" name="location" id="location" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Location">
            </div>
        </div>
        <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <button type="submit" name="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_متابة-الحوادث.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../backend/متابة-الحوادث.php',
            data: $(this).serialize(),
            success: function(data) {
                if (data === 'success') {
                    window.location.href = 'list_متابة-الحوادث.php';
                } else {
                    alert('Error creating record');
                }
            }
        });
    });
});


**backend/متابة-الحوادث.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    // Insert data into database
    $sql = "INSERT INTO متابة_الحوادث (name, description, date, time, location) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $description, $date, $time, $location);
    $stmt->execute();

    // Return success message
    echo 'success';
}