<?php
// edit_مستودعات.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_مستودعات.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مستودع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 mt-10 bg-slate-800 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4">تعديل مستودع</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم المستودع</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">وصف المستودع</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/مستودعات.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    name: $('#name').val(),
                    description: $('#description').val()
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مستودعات.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function() {
                        window.location.href = 'list_مستودعات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>