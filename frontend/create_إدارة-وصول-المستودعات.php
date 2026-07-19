<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Define module slug
$mod_slug = 'إدارة-وصول-المستودعات';

// Define form fields
$form_fields = [
    'warehouse_name' => '',
    'access_level' => '',
    'description' => '',
];

// Define form errors
$form_errors = [];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إدارة وصول المستودعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">إضافة إدارة وصول المستودعات</h1>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="warehouse_name" class="block text-sm font-medium mb-2">اسم المستودع</label>
                <input type="text" id="warehouse_name" name="warehouse_name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $form_fields['warehouse_name']; ?>">
                <?php if (isset($form_errors['warehouse_name'])) : ?>
                    <p class="mt-2 text-sm text-red-600"><?php echo $form_errors['warehouse_name']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="access_level" class="block text-sm font-medium mb-2">مستوى الوصول</label>
                <select id="access_level" name="access_level" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- اختر مستوى الوصول --</option>
                    <option value="admin">مدير</option>
                    <option value="user">مستخدم</option>
                </select>
                <?php if (isset($form_errors['access_level'])) : ?>
                    <p class="mt-2 text-sm text-red-600"><?php echo $form_errors['access_level']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?php echo $form_fields['description']; ?></textarea>
                <?php if (isset($form_errors['description'])) : ?>
                    <p class="mt-2 text-sm text-red-600"><?php echo $form_errors['description']; ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 rounded-md hover:bg-indigo-700">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/إدارة-وصول-المستودعات.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>