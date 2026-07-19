**edit_متابة-الحوادث.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/متابة-الحوادث.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل متابة الحوادث</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">تعديل متابة الحوادث</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-900">عنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $record['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">وصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-300 rounded-md"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/متابة-الحوادث.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
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


**backend/متابة-الحوادث.php**

<?php
// Check if ID is set
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT)) {
    exit;
}

// Get ID
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Fetch existing record details
$record = array(
    'id' => $id,
    'title' => 'عنوان',
    'description' => 'وصف',
    // Add more fields as needed
);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($record);
exit;
?>