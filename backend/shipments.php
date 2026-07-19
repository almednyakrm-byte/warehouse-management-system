<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
function isLoggedIn() {
    // Replace with your actual session management
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    // Replace with your actual session management
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $shipmentId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // SQL query structure: Select all shipments or a single shipment by ID
    if ($shipmentId) {
        $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = :id');
        $stmt->bindParam(':id', $shipmentId);
        $stmt->execute();
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$shipment) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Shipment not found']);
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode($shipment);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM shipments');
        $stmt->execute();
        $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($shipments);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $requiredFields = ['customer_name', 'address', 'status'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // SQL query structure: Insert a new shipment
    $stmt = $pdo->prepare('INSERT INTO shipments (customer_name, address, status) VALUES (:customer_name, :address, :status)');
    $stmt->bindParam(':customer_name', $data['customer_name']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':status', $data['status']);
    $stmt->execute();

    // Get the ID of the newly inserted shipment
    $shipmentId = $pdo->lastInsertId();

    // Return the newly inserted shipment
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = :id');
    $stmt->bindParam(':id', $shipmentId);
    $stmt->execute();
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($shipment);
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $requiredFields = ['id', 'customer_name', 'address', 'status'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // SQL query structure: Update a shipment
    $stmt = $pdo->prepare('UPDATE shipments SET customer_name = :customer_name, address = :address, status = :status WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':customer_name', $data['customer_name']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':status', $data['status']);
    $stmt->execute();

    // Check if the shipment was updated
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Shipment not found']);
        exit;
    }

    // Return the updated shipment
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($shipment);
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required field: id']);
        exit;
    }

    // SQL query structure: Delete a shipment
    $stmt = $pdo->prepare('DELETE FROM shipments WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();

    // Check if the shipment was deleted
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Shipment not found']);
        exit;
    }

    http_response_code(204);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Shipment deleted successfully']);
}