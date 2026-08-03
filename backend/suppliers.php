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

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Process GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $supplier_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($supplier_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid supplier ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM suppliers WHERE id = :id');
    $stmt->bindParam(':id', $supplier_id);
    $stmt->execute();

    // Process output
    $supplier = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($supplier === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Supplier not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($supplier);
    exit;
}

// Process POST request
if ($method === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'] ?? null, FILTER_SANITIZE_STRING);
    if ($name === null || $email === null || $phone === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO suppliers (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Process output
    $supplier_id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $supplier_id]);
    exit;
}

// Process PUT request
if ($method === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    // Validate and sanitize input
    $supplier_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'] ?? null, FILTER_SANITIZE_STRING);
    if ($supplier_id === false || $name === null || $email === null || $phone === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE suppliers SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $supplier_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Supplier not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Supplier updated successfully']);
    exit;
}

// Process DELETE request
if ($method === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    // Validate and sanitize input
    $supplier_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($supplier_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid supplier ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM suppliers WHERE id = :id');
    $stmt->bindParam(':id', $supplier_id);
    $stmt->execute();

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Supplier not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Supplier deleted successfully']);
    exit;
}

// Handle invalid request method
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);