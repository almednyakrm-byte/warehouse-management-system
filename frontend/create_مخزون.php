<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
include '../backend/db.php';

// Define the module slug
$mod_slug = 'مخزون';

// Define the page title
$page_title = 'Create مخزون';

// Include the header file
include 'header.php';
?>

<main class="h-full overflow-y-auto p-4">
    <div class="container mx-auto">
        <div class="grid w-full gap-5">
            <div class="flex flex-col">
                <h2 class="text-lg font-bold text-indigo-500">Create مخزون</h2>
                <p class="text-sm text-slate-700">Please fill in the form below to create a new مخزون record.</p>
            </div>
            <form id="create-form" class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label for="name" class="text-sm text-slate-700">Name</label>
                    <input type="text" id="name" name="name" class="block w-full rounded-lg border border-slate-300 p-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="description" class="text-sm text-slate-700">Description</label>
                    <textarea id="description" name="description" class="block w-full rounded-lg border border-slate-300 p-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="quantity" class="text-sm text-slate-700">Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="block w-full rounded-lg border border-slate-300 p-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="unit_price" class="text-sm text-slate-700">Unit Price</label>
                    <input type="number" id="unit_price" name="unit_price" class="block w-full rounded-lg border border-slate-300 p-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <button type="submit" class="rounded-lg bg-indigo-500 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500">Create مخزون</button>
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
                url: '../backend/مخزون.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include the footer file
include 'footer.php';
?>