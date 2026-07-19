<?php
// create_إدارة-تسجيل.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'إدارة-تسجيل';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إدارة تسجيل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 mt-10 bg-slate-100 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-indigo-500 mb-4">إضافة إدارة تسجيل</h2>
        <form id="create-form">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-900">اسم الإدارة</label>
                    <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-slate-900 bg-slate-100 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-900">وصف الإدارة</label>
                    <textarea id="description" name="description" class="block w-full p-2 mt-1 text-slate-900 bg-slate-100 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-900">حالة الإدارة</label>
                    <select id="status" name="status" class="block w-full p-2 mt-1 text-slate-900 bg-slate-100 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="active">فعال</option>
                        <option value="inactive">غير فعال</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full p-2 mt-4 text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/إدارة-تسجيل.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>