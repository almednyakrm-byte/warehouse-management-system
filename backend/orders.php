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
    $orderId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($orderId === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid order ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
    $stmt->bindParam(':id', $orderId);
    $stmt->execute();

    // Process output
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($order === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($order);
    exit;
}

// Handle POST requests
if ($method == 'POST') {
    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $customerId = filter_var($data['customer_id'] ?? null, FILTER_VALIDATE_INT);
    $orderDate = filter_var($data['order_date'] ?? null, FILTER_VALIDATE_DATE);
    $total = filter_var($data['total'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($customerId === false || $orderDate === false || $total === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO orders (customer_id, order_date, total) VALUES (:customer_id, :order_date, :total)');
    $stmt->bindParam(':customer_id', $customerId);
    $stmt->bindParam(':order_date', $orderDate);
    $stmt->bindParam(':total', $total);
    $stmt->execute();

    // Process output
    $orderId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $orderId]);
    exit;
}

// Handle PUT requests
if ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $orderId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $customerId = filter_var($data['customer_id'] ?? null, FILTER_VALIDATE_INT);
    $orderDate = filter_var($data['order_date'] ?? null, FILTER_VALIDATE_DATE);
    $total = filter_var($data['total'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($orderId === false || $customerId === false || $orderDate === false || $total === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE orders SET customer_id = :customer_id, order_date = :order_date, total = :total WHERE id = :id');
    $stmt->bindParam(':id', $orderId);
    $stmt->bindParam(':customer_id', $customerId);
    $stmt->bindParam(':order_date', $orderDate);
    $stmt->bindParam(':total', $total);
    $stmt->execute();

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Order updated successfully']);
    exit;
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $orderId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($orderId === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid order ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM orders WHERE id = :id');
    $stmt->bindParam(':id', $orderId);
    $stmt->execute();

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Order deleted successfully']);
    exit;
}

http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);