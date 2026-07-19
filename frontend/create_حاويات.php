**create_حاويات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include 'navigation.php';
?>

<div class="container mx-auto p-4 mt-6">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create New حاويات</h1>

    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Name">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" placeholder="Description"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="capacity">Capacity</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="capacity" type="number" placeholder="Capacity">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="size">Size</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="size" type="number" placeholder="Size">
        </div>

        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button" id="submit-btn">Create</button>
    </form>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('create-form');
        const submitBtn = document.getElementById('submit-btn');

        submitBtn.addEventListener('click', function(event) {
            event.preventDefault();

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', '../backend/حاويات.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.href = 'list_حاويات.php';
                } else {
                    console.error(xhr.responseText);
                }
            };
            xhr.send(formData);
        });
    });
</script>


**backend/حاويات.php**

<?php
// Include database connection
include 'db.php';

// Check if form data is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];
    $size = $_POST['size'];

    // Insert data into database
    $query = "INSERT INTO حاويات (name, description, capacity, size) VALUES ('$name', '$description', '$capacity', '$size')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'Record created successfully!';
    } else {
        echo 'Error creating record: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>