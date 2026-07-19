<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أوامر شحن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
    <header class="bg-indigo-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl font-bold mb-4">أوامر شحن</h1>
        <div class="flex justify-between mb-4">
            <a href="create_أوامر-شحن.php" class="bg-indigo-500 text-white px-4 py-2 rounded">إضافة جديد</a>
            <input type="search" id="search" class="px-4 py-2 rounded" placeholder="بحث...">
        </div>
        <table id="records" class="w-full text-right">
            <thead class="bg-slate-700 text-white">
                <tr>
                    <th>العمود 1</th>
                    <th>العمود 2</th>
                    <th>العمود 3</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        const searchInput = document.getElementById('search');
        const recordsBody = document.getElementById('records-body');

        // Fetch records from backend
        fetch('../backend/أوامر-شحن.php')
            .then(response => response.json())
            .then(data => {
                const recordsHtml = data.map(record => `
                    <tr>
                        <td>${record.column1}</td>
                        <td>${record.column2}</td>
                        <td>${record.column3}</td>
                        <td>
                            <a href="edit_أوامر-شحن.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                            <button class="text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    </tr>
                `).join('');
                recordsBody.innerHTML = recordsHtml;
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/أوامر-شحن.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove record from table
                    const recordRow = document.querySelector(`#records-body tr:nth-child(${id})`);
                    recordRow.remove();
                } else {
                    console.error('Error deleting record:', data.error);
                }
            });
        }

        // Search records
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const recordsRows = recordsBody.children;

            for (const row of recordsRows) {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>