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
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">Edit Order</h2>
        <form id="edit-order-form">
            <div class="mt-4">
                <label for="order_name" class="block text-sm text-gray-300">Order Name</label>
                <input type="text" id="order_name" name="order_name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <label for="order_description" class="block text-sm text-gray-300">Order Description</label>
                <textarea id="order_description" name="order_description" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mt-4">
                <label for="order_status" class="block text-sm text-gray-300">Order Status</label>
                <select id="order_status" name="order_status" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">Update Order</button>
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
                    $('#order_name').val(data.order_name);
                    $('#order_description').val(data.order_description);
                    $('#order_status').val(data.order_status);
                }
            });

            $('#edit-order-form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    order_name: $('#order_name').val(),
                    order_description: $('#order_description').val(),
                    order_status: $('#order_status').val()
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