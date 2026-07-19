<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'عائدات';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<main class="h-screen bg-slate-100">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4"><?= $page_title ?></h1>
        <form id="create-form" class="bg-slate-900 rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="date" class="block text-sm text-indigo-500 font-bold mb-2">Date</label>
                    <input type="date" id="date" name="date" class="block w-full p-2 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="amount" class="block text-sm text-indigo-500 font-bold mb-2">Amount</label>
                    <input type="number" id="amount" name="amount" class="block w-full p-2 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="block text-sm text-indigo-500 font-bold mb-2">Description</label>
                    <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <button type="submit" class="w-full mt-4 p-2 text-sm text-indigo-500 font-bold bg-slate-900 border border-indigo-500 rounded-lg hover:bg-indigo-500 hover:text-slate-900 focus:ring-indigo-500 focus:border-indigo-500">Create</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/عائدات.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_عائدات.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>