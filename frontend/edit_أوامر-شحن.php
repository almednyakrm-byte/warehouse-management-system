<?php
// edit_أوامر-شحن.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_أوامر-شحن.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل أوامر شحن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-white rounded shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل أوامر شحن</h2>
        <form id="edit-form">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="order_number" class="block text-sm text-slate-700">رقم الطلب</label>
                    <input type="text" id="order_number" name="order_number" class="mt-1 block w-full rounded-md border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="customer_name" class="block text-sm text-slate-700">اسم العميل</label>
                    <input type="text" id="customer_name" name="customer_name" class="mt-1 block w-full rounded-md border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="shipping_address" class="block text-sm text-slate-700">عنوان الشحن</label>
                    <input type="text" id="shipping_address" name="shipping_address" class="mt-1 block w-full rounded-md border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="shipping_date" class="block text-sm text-slate-700">تاريخ الشحن</label>
                    <input type="date" id="shipping_date" name="shipping_date" class="mt-1 block w-full rounded-md border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/أوامر-شحن.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#order_number').val(data.order_number);
                    $('#customer_name').val(data.customer_name);
                    $('#shipping_address').val(data.shipping_address);
                    $('#shipping_date').val(data.shipping_date);
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/أوامر-شحن.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_أوامر-شحن.php';
                    }
                });
            });
        });
    </script>
</body>
</html>