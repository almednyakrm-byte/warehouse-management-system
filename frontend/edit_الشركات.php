**edit_الشركات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get company ID from URL
$id = $_GET['id'];

// Fetch company details via AJAX
$js = '
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "../backend/الشركات.php?id=' . $id . '",
            dataType: "json",
            success: function(data) {
                $("#name").val(data.name);
                $("#description").val(data.description);
                $("#address").val(data.address);
            }
        });
    });
';

// Include JavaScript code
echo '<script>' . $js . '</script>';

// Form submission handler
$js = '
    $(document).ready(function() {
        $("#edit-form").submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: "PUT",
                url: "../backend/الشركات.php",
                data: $(this).serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        window.location.href = "list_الشركات.php";
                    } else {
                        alert("Error updating company");
                    }
                }
            });
        });
    });
';

// Include JavaScript code
echo '<script>' . $js . '</script>';

?>

<!-- Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Company</h2>
    <form id="edit-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Company Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Company Name">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Description"></textarea>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Address">
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">Update Company</button>
    </form>
</div>

**Note:** This code assumes that you have a backend PHP script (`../backend/الشركات.php`) that handles the GET and PUT requests. The backend script should return a JSON response with the company details on GET request and a JSON response with a `success` property on PUT request.