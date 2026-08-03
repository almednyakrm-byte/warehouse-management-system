<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all warehouses
    $stmt = $pdo->prepare('SELECT * FROM warehouses');
    $stmt->execute();
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($warehouses);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create new warehouse
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['name']) || !isset($data['address'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO warehouses (name, address) VALUES (:name, :address)');
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Warehouse created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update existing warehouse
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['address'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE warehouses SET name = :name, address = :address WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Warehouse updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete existing warehouse
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM warehouses WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Warehouse deleted successfully']);
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}