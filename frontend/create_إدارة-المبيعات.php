**create_إدارة-المبيعات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO إدارة_المبيعات (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $email, $phone, $address);
        if ($stmt->execute()) {
            // Redirect back to list page
            header('Location: list_إدارة-المبيعات.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<!-- Create new إدارة المبيعات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة جديد</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم</label>
            <input type="text" id="name" name="name" class="block w-full p-2 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-slate-900 text-sm font-bold mb-2">بريد إلكتروني</label>
            <input type="email" id="email" name="email" class="block w-full p-2 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-slate-900 text-sm font-bold mb-2">هاتف</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-slate-900 text-sm font-bold mb-2">عنوان</label>
            <textarea id="address" name="address" class="block w-full p-2 border border-slate-300 rounded-lg" required></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/إدارة-المبيعات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_إدارة-المبيعات.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/إدارة-المبيعات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Insert data into database
    $sql = "INSERT INTO إدارة_المبيعات (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $name, $email, $phone, $address);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error inserting data']);
    }
}