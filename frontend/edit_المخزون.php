**edit_المخزون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/المخزون.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Set page title
$page_title = 'Edit ' . $record['name'];

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['quantity'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Save Changes</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/المخزون.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('quantity').value = data.quantity;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/المخزون.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                quantity: document.getElementById('quantity').value
            })
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_المخزون.php';
            })
            .catch(error => console.error(error));
    });
</script>


**backend/المخزون.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new PDO('dsn', 'username', 'password');

// Prepare query
$stmt = $conn->prepare('SELECT * FROM المخزون WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();

// Fetch record
$record = $stmt->fetch();

// Return record as JSON
echo json_encode($record);

// Close connection
$conn = null;


**backend/edit_المخزون.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get id
$id = $_GET['id'];

// Get data from request body
$data = json_decode(file_get_contents('php://input'), true);

// Update record
$conn = new PDO('dsn', 'username', 'password');
$stmt = $conn->prepare('UPDATE المخزون SET name = :name, quantity = :quantity WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->bindParam(':name', $data['name']);
$stmt->bindParam(':quantity', $data['quantity']);
$stmt->execute();

// Return success message
http_response_code(200);
echo 'Record updated successfully';

// Close connection
$conn = null;