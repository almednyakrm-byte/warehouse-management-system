<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all stock records
    $stmt = $pdo->prepare('SELECT * FROM المخزون');
    $stmt->execute();
    $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return stock records
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($stock);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['product_id']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $product_id = (int) $input['product_id'];
    $quantity = (int) $input['quantity'];

    // Insert new stock record
    $stmt = $pdo->prepare('INSERT INTO المخزون (product_id, quantity) VALUES (:product_id, :quantity)');
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock record created successfully'));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['product_id']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];
    $product_id = (int) $input['product_id'];
    $quantity = (int) $input['quantity'];

    // Update stock record
    $stmt = $pdo->prepare('UPDATE المخزون SET product_id = :product_id, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock record updated successfully'));
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];

    // Delete stock record
    $stmt = $pdo->prepare('DELETE FROM المخزون WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock record deleted successfully'));
}

// Return error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}