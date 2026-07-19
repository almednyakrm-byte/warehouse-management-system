<?php
// edit_orders.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_orders.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-2xl text-gray-700 mb-4">Edit Order</h2>
        <form id="edit-order-form">
            <div class="mb-4">
                <label for="order_id" class="block text-gray-700 text-sm font-bold mb-2">Order ID</label>
                <input type="text" id="order_id" name="order_id" readonly class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="order_date" class="block text-gray-700 text-sm font-bold mb-2">Order Date</label>
                <input type="date" id="order_date" name="order_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Total</label>
                <input type="number" id="total" name="total" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Order</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/orders.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#order_id').val(data.order_id);
                    $('#customer_name').val(data.customer_name);
                    $('#order_date').val(data.order_date);
                    $('#total').val(data.total);
                }
            });

            $('#edit-order-form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    'order_id': $('#order_id').val(),
                    'customer_name': $('#customer_name').val(),
                    'order_date': $('#order_date').val(),
                    'total': $('#total').val()
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/orders.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_orders.php';
                    }
                });
            });
        });
    </script>
</body>
</html>