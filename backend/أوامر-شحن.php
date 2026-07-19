<?php
// Import database connection
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

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is empty
if (empty($data)) {
    $data = $_POST;
}

// Switch based on request method
switch ($method) {
    case 'GET':
        // Validate and sanitize input
        $orderId = isset($data['order_id']) ? filter_var($data['order_id'], FILTER_SANITIZE_NUMBER_INT) : null;

        // Check if order ID is provided
        if ($orderId) {
            // Prepare SQL query to get order by ID
            $stmt = $pdo->prepare('SELECT * FROM اوامر_شحن WHERE order_id = :order_id');
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();

            // Fetch order data
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if order exists
            if ($order) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($order);
            } else {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Order not found']);
            }
        } else {
            // Prepare SQL query to get all orders
            $stmt = $pdo->prepare('SELECT * FROM اوامر_شحن');
            $stmt->execute();

            // Fetch all orders
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($orders);
        }
        break;

    case 'POST':
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Validate and sanitize input
        $customerName = isset($data['customer_name']) ? filter_var($data['customer_name'], FILTER_SANITIZE_STRING) : null;
        $orderDate = isset($data['order_date']) ? filter_var($data['order_date'], FILTER_SANITIZE_STRING) : null;
        $totalCost = isset($data['total_cost']) ? filter_var($data['total_cost'], FILTER_SANITIZE_NUMBER_FLOAT) : null;

        // Check if required fields are provided
        if ($customerName && $orderDate && $totalCost) {
            // Prepare SQL query to insert new order
            $stmt = $pdo->prepare('INSERT INTO اوامر_شحن (customer_name, order_date, total_cost) VALUES (:customer_name, :order_date, :total_cost)');
            $stmt->bindParam(':customer_name', $customerName);
            $stmt->bindParam(':order_date', $orderDate);
            $stmt->bindParam(':total_cost', $totalCost);
            $stmt->execute();

            // Get inserted order ID
            $orderId = $pdo->lastInsertId();

            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['order_id' => $orderId]);
        } else {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
        }
        break;

    case 'PUT':
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Validate and sanitize input
        $orderId = isset($data['order_id']) ? filter_var($data['order_id'], FILTER_SANITIZE_NUMBER_INT) : null;
        $customerName = isset($data['customer_name']) ? filter_var($data['customer_name'], FILTER_SANITIZE_STRING) : null;
        $orderDate = isset($data['order_date']) ? filter_var($data['order_date'], FILTER_SANITIZE_STRING) : null;
        $totalCost = isset($data['total_cost']) ? filter_var($data['total_cost'], FILTER_SANITIZE_NUMBER_FLOAT) : null;

        // Check if order ID and at least one field are provided
        if ($orderId && ($customerName || $orderDate || $totalCost)) {
            // Prepare SQL query to update order
            $stmt = $pdo->prepare('UPDATE اوامر_شحن SET customer_name = COALESCE(:customer_name, customer_name), order_date = COALESCE(:order_date, order_date), total_cost = COALESCE(:total_cost, total_cost) WHERE order_id = :order_id');
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':customer_name', $customerName);
            $stmt->bindParam(':order_date', $orderDate);
            $stmt->bindParam(':total_cost', $totalCost);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Order updated successfully']);
        } else {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
        }
        break;

    case 'DELETE':
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Validate and sanitize input
        $orderId = isset($data['order_id']) ? filter_var($data['order_id'], FILTER_SANITIZE_NUMBER_INT) : null;

        // Check if order ID is provided
        if ($orderId) {
            // Prepare SQL query to delete order
            $stmt = $pdo->prepare('DELETE FROM اوامر_شحن WHERE order_id = :order_id');
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Order deleted successfully']);
        } else {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
        }
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method not allowed']);
        break;
}