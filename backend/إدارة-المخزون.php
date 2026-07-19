<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if ($_SESSION['role'] != 'admin' && $_GET['action'] == 'edit' || $_GET['action'] == 'delete') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        // Get single item by ID
        $stmt = $pdo->prepare('SELECT * FROM إدارة_المخزون WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $item = $stmt->fetch();
        if ($item) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } else {
        // Get all items
        $stmt = $pdo->prepare('SELECT * FROM إدارة_المخزون');
        $stmt->execute();
        $items = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($items);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($input['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new item
    $stmt = $pdo->prepare('INSERT INTO إدارة_المخزون (name, quantity) VALUES (:name, :quantity)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Item created successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($input['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Update existing item
    $stmt = $pdo->prepare('UPDATE إدارة_المخزون SET name = :name, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Item updated successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete existing item
    $stmt = $pdo->prepare('DELETE FROM إدارة_المخزون WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Item deleted successfully'));
}