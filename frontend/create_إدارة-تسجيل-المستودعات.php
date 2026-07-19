**create_إدارة-تسجيل-المستودعات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Form data
$data = array(
    'name' => '',
    'address' => '',
    'phone' => '',
    'email' => '',
);

// Form validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['name'] = $_POST['name'];
    $data['address'] = $_POST['address'];
    $data['phone'] = $_POST['phone'];
    $data['email'] = $_POST['email'];

    // AJAX request
    $ajax_url = '../backend/إدارة-تسجيل-المستودعات.php';
    $ajax_data = array(
        'name' => $data['name'],
        'address' => $data['address'],
        'phone' => $data['phone'],
        'email' => $data['email'],
    );

    $ajax_response = json_decode(send_ajax_request($ajax_url, $ajax_data), true);

    if ($ajax_response['success']) {
        header('Location: list_إدارة-تسجيل-المستودعات.php');
        exit;
    } else {
        $errors = $ajax_response['errors'];
    }
}

// Send AJAX request
function send_ajax_request($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Form HTML
?>

<div class="container mx-auto p-4">
    <div class="bg-slate-100 rounded-lg p-4">
        <h2 class="text-2xl font-bold text-indigo-500 mb-4">إضافة تسجيل مستودع جديد</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">اسم المستودع</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-300 rounded-lg" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-slate-700">عنوان المستودع</label>
                <input type="text" id="address" name="address" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-300 rounded-lg" value="<?= $data['address'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-700">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-300 rounded-lg" value="<?= $data['phone'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-300 rounded-lg" value="<?= $data['email'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
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
                url: '../backend/إدارة-تسجيل-المستودعات.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_إدارة-تسجيل-المستودعات.php';
                    } else {
                        var errors = response.errors;
                        // Display errors
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


**backend/إدارة-تسجيل-المستودعات.php**

<?php
// Database connection
include 'db_connection.php';

// Form data
$data = array(
    'name' => $_POST['name'],
    'address' => $_POST['address'],
    'phone' => $_POST['phone'],
    'email' => $_POST['email'],
);

// Validation
$errors = array();

if (empty($data['name'])) {
    $errors[] = 'اسم المستودع مطلوب';
}

if (empty($data['address'])) {
    $errors[] = 'عنوان المستودع مطلوب';
}

if (empty($data['phone'])) {
    $errors[] = 'رقم الهاتف مطلوب';
}

if (empty($data['email'])) {
    $errors[] = 'البريد الإلكتروني مطلوب';
}

if (empty($errors)) {
    // Insert data into database
    $query = "INSERT INTO إدارة_تسجيل_المستودعات (name, address, phone, email) VALUES (:name, :address, :phone, :email)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($data);
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'errors' => $errors));
}
?>