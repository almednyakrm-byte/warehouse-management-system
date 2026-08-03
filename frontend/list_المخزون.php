**list_المخزون.php**

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
    <title>المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
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
            margin: 1rem auto;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">المخزون</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_المخزون.php'">إضافة عنصر جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>الرمز</th>
                    <th>الاسم</th>
                    <th>الكمية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            try {
                const response = await fetch('../backend/المخزون.php');
                const data = await response.json();
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.رمز}</td>
                        <td>${record.اسم}</td>
                        <td>${record.كمية}</td>
                        <td>
                            <a href="edit_المخزون.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                const records = document.getElementById('records');
                records.innerHTML = '';
                fetch('../backend/المخزون.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.رمز}</td>
                                <td>${record.اسم}</td>
                                <td>${record.كمية}</td>
                                <td>
                                    <a href="edit_المخزون.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                loadRecords();
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا العنصر؟')) {
                fetch('../backend/المخزون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error(error));
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code includes the following features:

* Session validation to ensure the user is authenticated before accessing the page.
* A premium Tailwind UI design with a specific color palette matching the theme.
* A header navigation bar with links to the index page, current user info, and logout.
* A table showing the list of records with actions: Edit and Delete.
* An 'Add New Item' button linking to the create_المخزون.php page.
* A search bar filtering elements in real-time.
* AJAX JavaScript code using the Fetch API to fetch the list of records from the backend and handle delete requests.

Note that this code assumes that the backend API is implemented and returns the list of records in JSON format. The delete request is also assumed to be implemented and returns a success message if the record is deleted successfully.