<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get supplier ID from URL
$supplier_id = $_GET['id'];

// Set page title
$page_title = 'Edit Supplier';

// Include header
include 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Supplier</h3>
            <p class="mt-1 text-sm text-gray-600">Update supplier details.</p>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="edit-supplier-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="supplier_name" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                                <input type="text" id="supplier_name" name="supplier_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="supplier_email" class="block text-sm font-medium text-gray-700">Supplier Email</label>
                                <input type="email" id="supplier_email" name="supplier_email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="supplier_phone" class="block text-sm font-medium text-gray-700">Supplier Phone</label>
                                <input type="text" id="supplier_phone" name="supplier_phone" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Supplier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Fetch existing supplier details
    fetch('../backend/suppliers.php?id=<?php echo $supplier_id; ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('supplier_name').value = data.supplier_name;
            document.getElementById('supplier_email').value = data.supplier_email;
            document.getElementById('supplier_phone').value = data.supplier_phone;
        });

    // Submit form using AJAX
    document.getElementById('edit-supplier-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/suppliers.php', {
            method: 'PUT',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_suppliers.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>