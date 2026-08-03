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
    <title>نظام إدارة مخازن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-300 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-end">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
        <div class="text-3xl font-bold text-indigo-500 mt-4">مرحباً بك في نظام إدارة مخازن</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إجمالي المنتجات</div>
                <div class="text-2xl font-bold" id="total-products">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إجمالي الموردين</div>
                <div class="text-2xl font-bold" id="total-suppliers">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إجمالي المخازن</div>
                <div class="text-2xl font-bold" id="total-warehouses">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إجمالي المنتجات في المخازن</div>
                <div class="text-2xl font-bold" id="total-products-in-warehouses">0</div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إدارة المنتجات</div>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mt-2" onclick="location.href='products.php'">إدارة</button>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إدارة الموردين</div>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mt-2" onclick="location.href='suppliers.php'">إدارة</button>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold text-indigo-500">إدارة المخازن</div>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mt-2" onclick="location.href='warehouses.php'">إدارة</button>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-products').innerText = data.totalProducts;
                document.getElementById('total-suppliers').innerText = data.totalSuppliers;
                document.getElementById('total-warehouses').innerText = data.totalWarehouses;
                document.getElementById('total-products-in-warehouses').innerText = data.totalProductsInWarehouses;
            });
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</body>
</html>