<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'You are not logged in.']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize the database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize the input
    $orderId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if the order ID is provided
    if ($orderId) {
        // SQL query to select a single order
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $orderId);
        $stmt->execute();

        // Fetch the result
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the order exists
        if ($order) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($order);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Order not found.']);
        }
    } else {
        // SQL query to select all orders
        $stmt = $pdo->prepare('SELECT * FROM orders');
        $stmt->execute();

        // Fetch the results
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($orders);
    }
}

// Handle POST requests
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $customerId = filter_var($data['customer_id'] ?? null, FILTER_VALIDATE_INT);
    $orderDate = filter_var($data['order_date'] ?? null, FILTER_VALIDATE_DATE);
    $total = filter_var($data['total'] ?? null, FILTER_VALIDATE_FLOAT);

    // Check if the input is valid
    if ($customerId && $orderDate && $total) {
        // SQL query to insert a new order
        $stmt = $pdo->prepare('INSERT INTO orders (customer_id, order_date, total) VALUES (:customer_id, :order_date, :total)');
        $stmt->bindParam(':customer_id', $customerId);
        $stmt->bindParam(':order_date', $orderDate);
        $stmt->bindParam(':total', $total);
        $stmt->execute();

        // Get the inserted ID
        $orderId = $pdo->lastInsertId();

        // SQL query to select the inserted order
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $orderId);
        $stmt->execute();

        // Fetch the result
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($order);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid input.']);
    }
}

// Handle PUT requests
if ($method == 'PUT') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'You do not have permission to edit orders.']);
        exit;
    }

    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $orderId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $customerId = filter_var($data['customer_id'] ?? null, FILTER_VALIDATE_INT);
    $orderDate = filter_var($data['order_date'] ?? null, FILTER_VALIDATE_DATE);
    $total = filter_var($data['total'] ?? null, FILTER_VALIDATE_FLOAT);

    // Check if the input is valid
    if ($orderId && $customerId && $orderDate && $total) {
        // SQL query to update an order
        $stmt = $pdo->prepare('UPDATE orders SET customer_id = :customer_id, order_date = :order_date, total = :total WHERE id = :id');
        $stmt->bindParam(':id', $orderId);
        $stmt->bindParam(':customer_id', $customerId);
        $stmt->bindParam(':order_date', $orderDate);
        $stmt->bindParam(':total', $total);
        $stmt->execute();

        // SQL query to select the updated order
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $orderId);
        $stmt->execute();

        // Fetch the result
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($order);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid input.']);
    }
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'You do not have permission to delete orders.']);
        exit;
    }

    // Validate and sanitize the input
    $orderId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if the order ID is provided
    if ($orderId) {
        // SQL query to delete an order
        $stmt = $pdo->prepare('DELETE FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $orderId);
        $stmt->execute();

        http_response_code(204);
        header('Content-Type: application/json');
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid input.']);
    }
}