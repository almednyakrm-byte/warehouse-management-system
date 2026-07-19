<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/shihnat' => array('GET' => 'getShihnat', 'POST' => 'createShihnat'),
    '/shihnat/:id' => array('GET' => 'getShihnatById', 'PUT' => 'updateShihnat', 'DELETE' => 'deleteShihnat')
);

// Route the request
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/') !== false) {
        $parts = explode('/', $route);
        if (count($parts) == 2 && $parts[0] == 'shihnat' && $parts[1] == (isset($input['id']) ? $input['id'] : '')) {
            $match = true;
            break;
        }
    } else {
        if ($route == 'shihnat' && (isset($input['id']) ? $input['id'] : '') == '') {
            $match = true;
            break;
        }
    }
}

if (!$match) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Get the method
$method = $_SERVER['REQUEST_METHOD'];

// Get the route
$route = $match ? $route : 'shihnat';

// Get the methods for the route
$methods = $routes[$route];

// Call the method
if (!isset($methods[$method])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

$methodName = $methods[$method];
$methodName();

function getShihnat() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM shihnat');
    $stmt->execute();
    $shihnat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($shihnat);
}

function getShihnatById() {
    global $db;
    $id = $_GET['id'];
    $stmt = $db->prepare('SELECT * FROM shihnat WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $shihnat = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$shihnat) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($shihnat);
}

function createShihnat() {
    global $db;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

    // Insert data
    $stmt = $db->prepare('INSERT INTO shihnat (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Shihnat created successfully'));
}

function updateShihnat() {
    global $db;
    $id = $_GET['id'];
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update data
    $stmt = $db->prepare('UPDATE shihnat SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Shihnat updated successfully'));
}

function deleteShihnat() {
    global $db;
    $id = $_GET['id'];
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete data
    $stmt = $db->prepare('DELETE FROM shihnat WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Shihnat deleted successfully'));
}