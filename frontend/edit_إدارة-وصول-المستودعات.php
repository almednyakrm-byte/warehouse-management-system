<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$record_id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if record exists
$query = "SELECT * FROM إدارة_وصول_المستودعات WHERE id = '$record_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_إدارة-وصول-المستودعات.php');
    exit;
}

// Fetch record details
$record = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة وصول المستودعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-slate-900 text-indigo-500">
        <h1 class="text-3xl font-bold mb-4">تعديل إدارة وصول المستودعات</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم المستودع</label>
                <input type="text" id="name" name="name" value="<?php echo $record['name']; ?>" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">وصف المستودع</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?php echo $record['description']; ?></textarea>
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 hover:bg-indigo-700 hover:text-slate-900 rounded-md">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing record details via GET
            // $.ajax({
            //     type: 'GET',
            //     url: '../backend/إدارة-وصول-المستودعات.php?id=<?php echo $record_id; ?>',
            //     success: function(data) {
            //         // Populate form fields
            //         $('#name').val(data.name);
            //         $('#description').val(data.description);
            //     }
            // });

            // Submit form using AJAX PUT request
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-وصول-المستودعات.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        window.location.href = 'list_إدارة-وصول-المستودعات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>