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
    '/get-all' => 'getAll',
    '/get-one' => 'getOne',
    '/create' => 'create',
    '/update' => 'update',
    '/delete' => 'delete'
);

// Get route
$match = null;
foreach ($routes as $route => $method) {
    if (strpos($_SERVER['REQUEST_URI'], $route) !== false) {
        $match = $route;
        break;
    }
}

// Call method
if ($match) {
    $method = $routes[$match];
    $method($input);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}

// Helper functions
function getAll($input) {
    global $db;
    
    // Validate input
    if (!isset($input['limit']) || !isset($input['offset'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $limit = (int) $input['limit'];
    $offset = (int) $input['offset'];
    
    // SQL query
    $stmt = $db->prepare("SELECT * FROM إدارة_تسجيل_المستودعات LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

function getOne($input) {
    global $db;
    
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    
    // SQL query
    $stmt = $db->prepare("SELECT * FROM إدارة_تسجيل_المستودعات WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Output
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}

function create($input) {
    global $db;
    
    // Validate input
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = $db->quote($input['name']);
    $address = $db->quote($input['address']);
    
    // SQL query
    $stmt = $db->prepare("INSERT INTO إدارة_تسجيل_المستودعات (name, address) VALUES ($name, $address)");
    $stmt->execute();
    
    // Output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

function update($input) {
    global $db;
    
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    $name = $db->quote($input['name']);
    $address = $db->quote($input['address']);
    
    // Check admin role
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $db->prepare("UPDATE إدارة_تسجيل_المستودعات SET name = $name, address = $address WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete($input) {
    global $db;
    
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    
    // Check admin role
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $db->prepare("DELETE FROM إدارة_تسجيل_المستودعات WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}