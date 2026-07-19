<?php
// Start the session
session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/connection.php';

// Define module slug
$mod_slug = 'products';

// Define page title
$page_title = 'Create Product';

// Include header
require_once 'header.php';
?>

<main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
    <div class="sm:justify-center md:justify-start sm:px-4 md:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-700">Create Product</h1>
    </div>
    <form id="create-product-form" class="mt-10 sm:mx-auto sm:max-w-md">
        <div class="bg-white shadow-md rounded-lg p-4">
            <div class="mb-4">
                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" id="product_name" name="product_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="product_description" class="block text-sm font-medium text-gray-700">Product Description</label>
                <textarea id="product_description" name="product_description" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="product_price" class="block text-sm font-medium text-gray-700">Product Price</label>
                <input type="number" id="product_price" name="product_price" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="product_category" class="block text-sm font-medium text-gray-700">Product Category</label>
                <select id="product_category" name="product_category" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories from database
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="file" id="product_image" name="product_image" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-700">Create Product</button>
        </div>
    </form>
</main>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/products.php',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>