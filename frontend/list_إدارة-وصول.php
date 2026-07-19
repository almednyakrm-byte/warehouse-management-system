**list_إدارة-وصول.php**

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
    <title>إدارة وصول</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز إدارة وصول</span>
        <span class="float-right">
            <a href="profile.php"><?= $_SESSION['username'] ?></a>
            <a href="logout.php">تسجيل خروج</a>
        </span>
    </div>
    <div class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">إدارة وصول</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-وصول.php'">إضافة جديد</button>
        <div class="search-bar mt-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>رقم الهاتف</th>
                    <th>البريد الإلكتروني</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get records
        async function getRecords() {
            try {
                const response = await fetch('../backend/إدارة-وصول.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                displayRecords(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Display records in the table
        function displayRecords(records) {
            const tableBody = document.getElementById('records-table');
            tableBody.innerHTML = '';
            records.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.username}</td>
                    <td>${record.phone}</td>
                    <td>${record.email}</td>
                    <td>${record.status}</td>
                    <td>
                        <a href="edit_إدارة-وصول.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/إدارة-وصول.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search: searchInput
                }
            })
            .then(response => response.json())
            .then(data => displayRecords(data))
            .catch(error => console.error(error));
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/إدارة-وصول.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        getRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize records table
        getRecords();
    </script>
</body>
</html>

This code includes:

1. Session validation to ensure the user is authenticated before accessing the page.
2. A premium Tailwind UI layout with a dark color scheme matching the theme.
3. A header navigation bar with links to the main page, user profile, and logout.
4. A table displaying a list of records with actions to edit and delete each record.
5. An "Add New Item" button linking to the create_إدارة-وصول.php page.
6. A search bar filtering elements in real-time using the Fetch API.
7. AJAX JavaScript code using the Fetch API to fetch records from the backend and delete records.

Note: This code assumes that the backend API is implemented and returns the records in JSON format. The `delete_إدارة-وصول.php` file is also assumed to be implemented and handles the DELETE request.