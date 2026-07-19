**edit_إدارة-تسجيل-المستودعات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$data = json_decode(file_get_contents('../backend/إدارة-تسجيل-المستودعات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة تسجيل المستودعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل إدارة تسجيل المستودعات</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم المستودع</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $data['name']; ?>">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-900">عنوان المستودع</label>
                <input type="text" id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $data['address']; ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $data['phone']; ?>">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-تسجيل-المستودعات.php',
                    data: formData,
                    success: function(response) {
                        if (response.status == 'success') {
                            window.location.href = 'list_إدارة-تسجيل-المستودعات.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إدارة-تسجيل-المستودعات.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    die('Invalid record ID');
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Get record details
$query = "SELECT * FROM إدارة_تسجيل_المستودعات WHERE id = '" . $_GET['id'] . "'";
$result = mysqli_query($conn, $query);

// Fetch record details
$data = mysqli_fetch_assoc($result);

// Close database connection
mysqli_close($conn);

// Output record details as JSON
echo json_encode($data);
?>