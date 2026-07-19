**create_إدارة-التخزين.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة إدارة تخزين جديدة</h2>
        <form id="create-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">اسم الإدارة</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="text-slate-900 font-bold">وصف الإدارة</label>
                    <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
                <a href="list_إدارة-التخزين.php" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-lg">إلغاء</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/إدارة-التخزين.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_إدارة-التخزين.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**إدارة-التخزين.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is sent
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);

    // Insert data into database
    $query = "INSERT INTO إدارة_التخزين (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Function to sanitize input data
function sanitize($data) {
    return trim(htmlspecialchars($data));
}
?>