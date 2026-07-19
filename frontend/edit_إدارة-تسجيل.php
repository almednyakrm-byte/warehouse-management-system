<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM إدارة_تسجيل WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_إدارة-تسجيل.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة تسجيل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-slate-900 p-8 rounded-xl shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل إدارة تسجيل</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-indigo-500 text-sm font-bold mb-2">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="bg-slate-900 border-indigo-500 text-indigo-500 rounded py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-indigo-500 text-sm font-bold mb-2">وصف الإدارة</label>
                <textarea id="description" name="description" class="bg-slate-900 border-indigo-500 text-indigo-500 rounded py-2 px-4 w-full h-32"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-slate-900 font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script>
        // Fetch existing record details
        fetch('../backend/إدارة-تسجيل.php?id=<?php echo $id; ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        document.getElementById('edit-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/إدارة-تسجيل.php', {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_إدارة-تسجيل.php';
                } else {
                    alert('Error updating record');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>