<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'مستودعات';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<main class="h-full overflow-y-auto p-4">
    <div class="container mx-auto">
        <div class="grid w-full gap-5">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-indigo-500">Create <?php echo $mod_slug; ?></h2>
            </div>
            <form id="create-form" method="post">
                <div class="grid grid-cols-1 gap-5">
                    <div class="bg-white rounded shadow-sm p-4">
                        <label for="name" class="text-sm font-medium text-slate-700">Name</label>
                        <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="bg-white rounded shadow-sm p-4">
                        <label for="description" class="text-sm font-medium text-slate-700">Description</label>
                        <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div class="bg-white rounded shadow-sm p-4">
                        <label for="address" class="text-sm font-medium text-slate-700">Address</label>
                        <input type="text" id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="bg-white rounded shadow-sm p-4">
                        <label for="phone" class="text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="bg-white rounded shadow-sm p-4">
                        <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                        <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end mt-5">
                    <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">Create</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?php echo $mod_slug; ?>.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>