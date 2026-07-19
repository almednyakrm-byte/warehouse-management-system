<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Prepare SQL query
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM inventory WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM inventory');
    }

    // Execute query
    $stmt->execute();

    // Process output
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $quantity = filter_var($data['quantity'] ?? null, FILTER_VALIDATE_INT);
    $price = filter_var($data['price'] ?? null, FILTER_VALIDATE_FLOAT);

    // Check for missing fields
    if (!$name || !$quantity || !$price) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO inventory (name, quantity, price) VALUES (:name, :quantity, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);

    // Execute query
    $stmt->execute();

    // Process output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Inventory item created successfully']);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $quantity = filter_var($data['quantity'] ?? null, FILTER_VALIDATE_INT);
    $price = filter_var($data['price'] ?? null, FILTER_VALIDATE_FLOAT);

    // Check for missing fields
    if (!$id || !$name || !$quantity || !$price) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE inventory SET name = :name, quantity = :quantity, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);

    // Execute query
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Inventory item updated successfully']);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for missing fields
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM inventory WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Inventory item deleted successfully']);
}

// Handle other requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}