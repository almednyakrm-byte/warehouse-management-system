**edit_إدارة-المخازن.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/إدارة-المخازن.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'تعديل إدارة المخازن';
$mod_slug = 'إدارة-المخازن';

// Include header and footer
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4"><?= $page_title ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold text-sm">إسم المخزن:</label>
                <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-slate-300 focus:outline-none focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 font-bold text-sm">وصف المخزن:</label>
                <textarea id="description" name="description" class="w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-slate-300 focus:outline-none focus:border-indigo-500"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">تعديل</button>
        </form>
    </div>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/إدارة-المخازن.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/إدارة-المخازن.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
include 'footer.php';
?>


**backend/إدارة-المخازن.php**

<?php
// Check if ID exists
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not found.']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);

if (empty($record)) {
    echo json_encode(['error' => 'Record not found.']);
    exit;
}

// Update record via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    update_record($id, $data);
    echo json_encode(['success' => true]);
    exit;
}

// Get record details
function get_record($id) {
    // Database query to get record details
    // ...
}

// Update record
function update_record($id, $data) {
    // Database query to update record
    // ...
}
?>