<?php
// Start the session
session_start();

// Validate the session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
include '../backend/db.php';

// Set the module slug
$mod_slug = 'شحنات';

// Set the page title
$page_title = 'Create شحنات';

// Include the header
include 'header.php';
?>

<!-- Main content -->
<main class="h-screen md:h-screen md:overflow-hidden overflow-auto md:pt-4 pt-6">
    <div class="container mx-auto p-4 pt-6 md:p-6">
        <h1 class="text-3xl text-slate-900 font-bold mb-4">Create شحنات</h1>
        <form id="create-شحنات-form">
            <div class="mb-4">
                <label for="customer_name" class="block text-sm font-medium text-slate-900">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="order_number" class="block text-sm font-medium text-slate-900">Order Number</label>
                <input type="text" id="order_number" name="order_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="shipping_address" class="block text-sm font-medium text-slate-900">Shipping Address</label>
                <textarea id="shipping_address" name="shipping_address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="shipping_date" class="block text-sm font-medium text-slate-900">Shipping Date</label>
                <input type="date" id="shipping_date" name="shipping_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-slate-900">Status</label>
                <select id="status" name="status" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="pending">Pending</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700 focus:ring-indigo-500 focus:ring-offset-indigo-200">Create شحنات</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-شحنات-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/شحنات.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_شحنات.php';
                }
            });
        });
    });
</script>

<?php
// Include the footer
include 'footer.php';
?>