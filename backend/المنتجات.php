<?php
require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'];
$authStatus = $_SESSION['authStatus'];

// Check if user is logged in and authorized
if (!$authStatus || ($userRole != 'admin' && $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE')) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Prepare SQL query to select all products
        $stmt = $pdo->prepare('SELECT * FROM products');
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return products in JSON format with 200 status code
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($products);
    } catch (PDOException $e) {
        // Return error message with 500 status code
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate input data
        if (!isset($inputData['name']) || !isset($inputData['price']) || !isset($inputData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $name = htmlspecialchars($inputData['name']);
        $price = htmlspecialchars($inputData['price']);
        $description = htmlspecialchars($inputData['description']);
        
        // Prepare SQL query to insert new product
        $stmt = $pdo->prepare('INSERT INTO products (name, price, description) VALUES (:name, :price, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return new product ID in JSON format with 201 status code
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } catch (PDOException $e) {
        // Return error message with 500 status code
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Validate input data
        if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['price']) || !isset($inputData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($inputData['id']);
        $name = htmlspecialchars($inputData['name']);
        $price = htmlspecialchars($inputData['price']);
        $description = htmlspecialchars($inputData['description']);
        
        // Prepare SQL query to update existing product
        $stmt = $pdo->prepare('UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return success message with 200 status code
        http_response_code(200);
        echo json_encode(array('message' => 'Product updated successfully'));
    } catch (PDOException $e) {
        // Return error message with 500 status code
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        // Validate input data
        if (!isset($inputData['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($inputData['id']);
        
        // Prepare SQL query to delete product
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return success message with 200 status code
        http_response_code(200);
        echo json_encode(array('message' => 'Product deleted successfully'));
    } catch (PDOException $e) {
        // Return error message with 500 status code
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}
?>