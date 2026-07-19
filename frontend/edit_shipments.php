<?php
// edit_shipments.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_shipments.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shipment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">Edit Shipment</h2>
        <form id="edit-shipment-form">
            <div class="mt-4">
                <label for="shipment_name" class="block text-sm text-slate-700">Shipment Name</label>
                <input type="text" id="shipment_name" name="shipment_name" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <label for="shipment_description" class="block text-sm text-slate-700">Shipment Description</label>
                <textarea id="shipment_description" name="shipment_description" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mt-4">
                <label for="shipment_status" class="block text-sm text-slate-700">Shipment Status</label>
                <select id="shipment_status" name="shipment_status" class="block w-full p-2 mt-1 text-sm text-slate-700 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending">Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700">Update Shipment</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-shipment-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/shipments.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('shipment_name').value = data.shipment_name;
                document.getElementById('shipment_description').value = data.shipment_description;
                document.getElementById('shipment_status').value = data.shipment_status;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/shipments.php?id=${id}`, {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_shipments.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>