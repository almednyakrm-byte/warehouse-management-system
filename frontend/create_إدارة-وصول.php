**create_إدارة-وصول.php**

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $access_level = trim($_POST['access_level']);

    // Validate fields
    if (empty($name) || empty($description) || empty($access_level)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO إدارة_وصول (name, description, access_level) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $name, $description, $access_level);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_إدارة-وصول.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة إدارة وصول جديدة</h2>
    <form action="" method="post" class="space-y-4">
        <div class="flex flex-col">
            <label for="name" class="text-slate-900 text-sm font-bold mb-2">اسم الإدارة</label>
            <input type="text" id="name" name="name" class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
        </div>
        <div class="flex flex-col">
            <label for="description" class="text-slate-900 text-sm font-bold mb-2">وصف الإدارة</label>
            <textarea id="description" name="description" class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required></textarea>
        </div>
        <div class="flex flex-col">
            <label for="access_level" class="text-slate-900 text-sm font-bold mb-2">مستوى الوصول</label>
            <select id="access_level" name="access_level" class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                <option value="">اختر مستوى الوصول</option>
                <option value="admin">مستوى إدارة</option>
                <option value="moderator">مستوى مراقب</option>
                <option value="user">مستوى مستخدم</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_إدارة-وصول.js**
javascript
// Get form
const form = document.querySelector('form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request
    fetch('../backend/إدارة-وصول.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        // Redirect back to list page
        window.location.href = 'list_إدارة-وصول.php';
    })
    .catch((error) => console.error(error));
});


**backend/إدارة-وصول.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $access_level = trim($_POST['access_level']);

    // Validate fields
    if (empty($name) || empty($description) || empty($access_level)) {
        echo json_encode(['error' => 'Please fill in all fields']);
        exit;
    } else {
        // Insert data into database
        $query = "INSERT INTO إدارة_وصول (name, description, access_level) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $name, $description, $access_level);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_إدارة-وصول.php');
        exit;
    }
}