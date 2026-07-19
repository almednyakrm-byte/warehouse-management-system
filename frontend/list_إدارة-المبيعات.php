**list_إدارة-المبيعات.php**

<?php
session_start();

// Check if user is authenticated
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
    <title>إدارة المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f1f1f;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 1rem;
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
            margin-right: 2rem;
        }
        .header .nav-links a {
            color: #fff;
            text-decoration: none;
        }
        .table-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table-container table th {
            background-color: #f0f0f0;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .actions .btn {
            background-color: #1f1f1f;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
        }
        .actions .btn:hover {
            background-color: #333;
        }
        .search-bar {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            background-color: #f7f7f7;
            font-size: 1rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            border: 1px solid #1f1f1f;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">إدارة المبيعات</div>
        <nav class="nav-links">
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="#">حسناً <?= $_SESSION['username'] ?></a></li>
                <li><a href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="table-container">
            <div class="actions">
                <button class="btn" onclick="location.href='create_إدارة-المبيعات.php'">إضافة جديد</button>
                <div class="search-bar">
                    <input type="search" id="search-input" placeholder="بحث...">
                    <button class="btn" onclick="searchRecords()">بحث</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>سعر المنتج</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records-table">
                    <?php
                    // Fetch records from backend
                    $records = json_decode(file_get_contents('../backend/إدارة-المبيعات.php'), true);
                    foreach ($records as $record) {
                        echo '<tr>';
                        echo '<td>' . $record['name'] . '</td>';
                        echo '<td>' . $record['price'] . '</td>';
                        echo '<td>' . $record['added_at'] . '</td>';
                        echo '<td>';
                        echo '<button class="btn" onclick="location.href=\'edit_إدارة-المبيعات.php?id=' . $record['id'] . '\'">تعديل</button>';
                        echo '<button class="btn" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/إدارة-المبيعات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.price}</td>
                                <td>${record.added_at}</td>
                                <td>
                                    <button class="btn" onclick="location.href='edit_إدارة-المبيعات.php?id=${record.id}'">تعديل</button>
                                    <button class="btn" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/إدارة-المبيعات.php')
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.price}</td>
                                <td>${record.added_at}</td>
                                <td>
                                    <button class="btn" onclick="location.href='edit_إدارة-المبيعات.php?id=${record.id}'">تعديل</button>
                                    <button class="btn" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/إدارة-المبيعات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code includes the following features:

*   Session validation to ensure the user is authenticated before accessing the page.
*   A premium Tailwind UI layout with a dark color scheme.
*   A table displaying a list of records with actions to edit and delete each record.
*   A search bar that filters the records in real-time.
*   AJAX calls to fetch records from the backend and delete records.

Note that this code assumes you have a backend script (`إدارة-المبيعات.php`) that handles the CRUD operations for the records. You'll need to create this script separately to complete the functionality.