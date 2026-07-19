**edit_إدارة-التخزين.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$js = "
<script>
    fetch('../backend/إدارة-التخزين.php?id=" . $id . "')
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error('Error:', error));
</script>
";

// Display form
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة التخزين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">تعديل إدارة التخزين</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">وصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <?php echo $js; ?>

    <script>
        document.getElementById('edit-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('../backend/إدارة-التخزين.php', {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_إدارة-التخزين.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>


**backend/إدارة-التخزين.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM إدارة_التخزين WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Return JSON response
echo json_encode($row);
?>


Note: Make sure to replace `../backend/إدارة-التخزين.php` with the actual path to your backend script, and `list_إدارة-التخزين.php` with the actual path to your list page. Also, make sure to validate user input and handle errors properly.