**create_مخازن.php**

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
    $capacity = trim($_POST['capacity']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description) && !empty($capacity)) {
        // Insert data into database
        $sql = "INSERT INTO مخازن (name, description, capacity) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $capacity]);

        // Redirect back to list page
        header('Location: list_مخازن.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New مخازن</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="text-slate-900 block mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="text-slate-900 block mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="capacity" class="text-slate-900 block mb-2">Capacity:</label>
            <input type="number" id="capacity" name="capacity" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مخازن.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مخازن.php';
                    } else {
                        alert('Error creating new مخازن');
                    }
                }
            });
        });
    });
</script>


**مخازن.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $capacity = trim($_POST['capacity']);

    // Insert data into database
    $sql = "INSERT INTO مخازن (name, description, capacity) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description, $capacity]);

    // Return success message
    echo 'success';
    exit;
}