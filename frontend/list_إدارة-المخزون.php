**list_إدارة-المخزون.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
        }
        .header .nav-links a {
            color: #fff;
            text-decoration: none;
        }
        .header .nav-links a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="logo">إدارة المخزون</h1>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="#">حسناً <?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">قائمة المخزون</h2>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-المخزون.php'">إضافة عنصر جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم العنصر</th>
                    <th>اسم العنصر</th>
                    <th>حالة العنصر</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get records
        async function getRecords() {
            const response = await fetch('../backend/إدارة-المخزون.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            return data;
        }

        // Search records
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            getRecords().then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    if (record.name.includes(searchValue) || record.code.includes(searchValue)) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.code}</td>
                            <td>${record.name}</td>
                            <td>${record.status}</td>
                            <td>${record.added_at}</td>
                            <td>
                                <a href="edit_إدارة-المخزون.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/إدارة-المخزون.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            });
            if (response.ok) {
                alert('تم حذف العنصر بنجاح');
                searchRecords();
            } else {
                alert('حدث خطأ أثناء حذف العنصر');
            }
        }

        // Fetch records on page load
        getRecords().then(data => {
            const records = document.getElementById('records');
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.code}</td>
                    <td>${record.name}</td>
                    <td>${record.status}</td>
                    <td>${record.added_at}</td>
                    <td>
                        <a href="edit_إدارة-المخزون.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        });
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a table showing the list of records with actions, a search bar, and AJAX JavaScript code to fetch records from the backend and delete records.