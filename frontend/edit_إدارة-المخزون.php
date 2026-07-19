<?php
// edit_إدارة-المخزون.php

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/إدارة-المخزون.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل إدارة المخزون</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">وصف الإدارة</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full py-2 px-4 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-700 rounded-md">حفظ التغييرات</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const response = await fetch('../backend/إدارة-المخزون.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: <?= $id ?>,
                    name: document.getElementById('name').value,
                    description: document.getElementById('description').value
                })
            });
            if (response.ok) {
                window.location.href = 'list_إدارة-المخزون.php';
            } else {
                console.error('Error updating record:', response.statusText);
            }
        });

        // Fetch existing record details via GET
        fetch('../backend/إدارة-المخزون.php?id=<?= $id ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            })
            .catch(error => console.error('Error fetching record:', error));
    </script>
</body>
</html>



// backend/إدارة-المخزون.php

// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace this with your actual database query
$record = [
    'id' => $id,
    'name' => 'إدارة المخزون',
    'description' => 'وصف لإدارة المخزون'
];

// Return record as JSON
header('Content-Type: application/json');
echo json_encode($record);

// Update record via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    // Update record in database
    // Replace this with your actual database query
    echo 'Record updated successfully';
}