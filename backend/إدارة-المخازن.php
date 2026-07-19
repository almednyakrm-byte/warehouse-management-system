<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array(
        '/stores' => 'getStores',
        '/stores/:id' => 'getStore',
    ),
    'POST' => array(
        '/stores' => 'createStore',
    ),
    'PUT' => array(
        '/stores/:id' => 'updateStore',
    ),
    'DELETE' => array(
        '/stores/:id' => 'deleteStore',
    ),
);

// Get route and method
$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Get route parameters
$matches = array();
if (preg_match('/\/stores\/(\d+)/', $route, $matches)) {
    $storeId = (int) $matches[1];
}

// Check if route and method exist
if (!isset($routes[$method][$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Call the corresponding function
$function = $routes[$method][$route];
$function();

// Helper functions

function getStores() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM stores');
    $stmt->execute();
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($stores);
}

function getStore() {
    global $db;
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $db->prepare('SELECT * FROM stores WHERE id = :id');
    $stmt->bindParam(':id', $storeId);
    $stmt->execute();
    $store = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$store) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($store);
}

function createStore() {
    global $db;
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Validate input
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
    // Insert data
    $stmt = $db->prepare('INSERT INTO stores (name, address) VALUES (:name, :address)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Store created successfully'));
}

function updateStore() {
    global $db;
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Validate input
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
    // Update data
    $stmt = $db->prepare('UPDATE stores SET name = :name, address = :address WHERE id = :id');
    $stmt->bindParam(':id', $storeId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Store updated successfully'));
}

function deleteStore() {
    global $db;
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete data
    $stmt = $db->prepare('DELETE FROM stores WHERE id = :id');
    $stmt->bindParam(':id', $storeId);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Store deleted successfully'));
}

?>