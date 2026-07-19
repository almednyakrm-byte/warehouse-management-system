**edit_إدارة-المبيعات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/إدارة-المبيعات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل إدارة المبيعات</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">وصف الإدارة</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:border-indigo-700">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-المبيعات.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_إدارة-المبيعات.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إدارة-المبيعات.php**

<?php
// Assuming you have a database connection established
// Fetch existing record details
$id = $_GET['id'];
$record = getRecordById($id);

// Return JSON response
echo json_encode($record);


**Note:** Replace `getRecordById` function with your actual database query to fetch the existing record details. Also, make sure to update the `backend/إدارة-المبيعات.php` file to handle the PUT request and update the record accordingly.