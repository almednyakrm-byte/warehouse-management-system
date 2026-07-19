<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق إدارة مخازن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-700 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl text-blue-500 font-bold">مرحباً!</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إجمالي المخازن</h2>
                <p id="total-warehouses" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إجمالي المخزون</h2>
                <p id="total-inventory" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إجمالي الطلبات</h2>
                <p id="total-orders" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إجمالي الموردين</h2>
                <p id="total-suppliers" class="text-3xl font-bold"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إدارة المخازن</h2>
                <a href="warehouses.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة المخازن</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إدارة المخزون</h2>
                <a href="inventory.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة المخزون</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إدارة الطلبات</h2>
                <a href="orders.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة الطلبات</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg font-bold mb-2">إدارة الموردين</h2>
                <a href="suppliers.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة الموردين</a>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-warehouses').innerText = data.totalWarehouses;
                document.getElementById('total-inventory').innerText = data.totalInventory;
                document.getElementById('total-orders').innerText = data.totalOrders;
                document.getElementById('total-suppliers').innerText = data.totalSuppliers;
            });

        // Logout function
        function logout() {
            fetch('api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        }
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.1), 0 0 10px rgba(0, 0, 0, 0.1), 0 0 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
    </style>
</body>
</html>