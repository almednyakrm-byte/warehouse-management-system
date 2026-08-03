**create_مستودعات.php**

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
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($name) || empty($address) || empty($phone) || empty($email)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مستودعات (name, address, phone, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $address, $phone, $email);
        $stmt->execute();

        // Redirect to list page
        header('Location: list_مستودعات.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';
?>

<!-- Create new مستودعات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New مستودعات</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-slate-900 text-sm font-bold mb-2">Address:</label>
            <input type="text" id="address" name="address" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-slate-900 text-sm font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_مستودعات.js**
javascript
// Get form element
const form = document.getElementById('create-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request
    fetch('../backend/مستودعات.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect to list page
            window.location.href = 'list_مستودعات.php';
        } else {
            // Display error message
            alert(data.error);
        }
    })
    .catch((error) => console.error(error));
});


**مستودعات.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Insert data into database
    $sql = "INSERT INTO مستودعات (name, address, phone, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $address, $phone, $email);
    $stmt->execute();

    // Return success response
    echo json_encode(array('success' => true));
} else {
    // Return error response
    echo json_encode(array('error' => 'Invalid request.'));
}
?>