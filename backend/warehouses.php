<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $warehouse_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all warehouses or a specific warehouse by id
    if ($warehouse_id) {
        $stmt = $pdo->prepare('SELECT * FROM warehouses WHERE id = :id');
        $stmt->bindParam(':id', $warehouse_id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM warehouses');
    }

    // Execute query and process output
    $stmt->execute();
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($warehouses);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $address = filter_var($data['address'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$name || !$address) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // SQL query structure: Insert new warehouse
    $stmt = $pdo->prepare('INSERT INTO warehouses (name, address) VALUES (:name, :address)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);

    // Execute query and process output
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Warehouse created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create warehouse']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $warehouse_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $address = filter_var($data['address'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$warehouse_id || (!$name && !$address)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // SQL query structure: Update existing warehouse
    $stmt = $pdo->prepare('UPDATE warehouses SET name = COALESCE(:name, name), address = COALESCE(:address, address) WHERE id = :id');
    $stmt->bindParam(':id', $warehouse_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);

    // Execute query and process output
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Warehouse updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update warehouse']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $warehouse_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for required fields
    if (!$warehouse_id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // SQL query structure: Delete existing warehouse
    $stmt = $pdo->prepare('DELETE FROM warehouses WHERE id = :id');
    $stmt->bindParam(':id', $warehouse_id);

    // Execute query and process output
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Warehouse deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete warehouse']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}