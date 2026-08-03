**edit_المنتجات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = '
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "../backend/المنتجات.php?id=' . $id . '",
                dataType: "json",
                success: function(data) {
                    $("#name").val(data.name);
                    $("#description").val(data.description);
                    $("#price").val(data.price);
                }
            });
        });
    </script>
';

// Include JavaScript code
echo $js;

?>

<!-- Edit Product Form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Product</h2>
    <form id="edit-product-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-slate-900">Price</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">Save Changes</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#edit-product-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "PUT",
                url: "../backend/المنتجات.php",
                data: $(this).serialize() + "&id=" + <?php echo $id; ?>,
                success: function() {
                    window.location.href = "list_المنتجات.php";
                }
            });
        });
    });
</script>


**backend/المنتجات.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get product ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Close connection
$conn->close();

// Output product details as JSON
echo json_encode($product);
?>


Note: Replace `'localhost'`, `'username'`, `'password'`, and `'database'` with your actual database credentials and name. Also, make sure to update the `list_المنتجات.php` URL to match your actual list page URL.