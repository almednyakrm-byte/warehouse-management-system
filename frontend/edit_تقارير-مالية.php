<?php
// edit_تقارير-مالية.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_تقارير-مالية.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل تقارير مالية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل تقارير مالية</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm text-slate-700 mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm text-slate-700 mb-2">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">تعديل</button>
        </form>
    </div>

    <script>
        const id = <?= $id ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/تقارير-مالية.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/تقارير-مالية.php`, {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_تقارير-مالية.php';
                } else {
                    console.error(data.error);
                }
            });
        });
    </script>
</body>
</html>