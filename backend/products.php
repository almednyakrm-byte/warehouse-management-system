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
    $productId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure
    if ($productId) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
        $product = $stmt->fetch();
        if (!$product) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Product not found']);
            exit;
        }
    } else {
        $stmt = $pdo->prepare('SELECT * FROM products');
        $stmt->execute();
        $products = $stmt->fetchAll();
    }

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    if ($productId) {
        echo json_encode($product);
    } else {
        echo json_encode($products);
    }
    exit;
}

// Handle POST requests
if ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'] ?? null, FILTER_VALIDATE_FLOAT);

    // Check for missing fields
    if (!$name || !$description || !$price) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO products (name, description, price) VALUES (:name, :description, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product created successfully']);
    exit;
}

// Handle PUT requests
if ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $productId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'] ?? null, FILTER_VALIDATE_FLOAT);

    // Check for missing fields
    if (!$productId || !$name || !$description || !$price) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch();
    if (!$product) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $productId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product updated successfully']);
    exit;
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $productId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for missing fields
    if (!$productId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch();
    if (!$product) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->bindParam(':id', $productId);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted successfully']);
    exit;
}

// Handle unknown methods
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
exit;