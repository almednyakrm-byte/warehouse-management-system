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
    '/get' => 'get',
    '/create' => 'create',
    '/update/:id' => 'update',
    '/delete/:id' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
foreach ($routes as $pattern => $method) {
    if (preg_match('/^' . $pattern . '$/', $route)) {
        $method($input);
        break;
    }
}

// Helper function to get data from database
function getData($query, $params = array()) {
    global $db;
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper function to insert data into database
function insertData($query, $params = array()) {
    global $db;
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $db->lastInsertId();
}

// Helper function to update data in database
function updateData($query, $params = array()) {
    global $db;
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
}

// Helper function to delete data from database
function deleteData($query, $params = array()) {
    global $db;
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
}

// GET method
function get($input) {
    global $db;
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $query = "SELECT * FROM إدارة_وصول_المستودعات WHERE id = :id";
    $params = array(':id' => $input['id']);
    $data = getData($query, $params);
    if (empty($data)) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// CREATE method
function create($input) {
    global $db;
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $query = "INSERT INTO إدارة_وصول_المستودعات (name, description) VALUES (:name, :description)";
    $params = array(':name' => $input['name'], ':description' => $input['description']);
    $id = insertData($query, $params);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

// UPDATE method
function update($input) {
    global $db;
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $query = "UPDATE إدارة_وصول_المستودعات SET name = :name, description = :description WHERE id = :id";
    $params = array(':id' => $input['id'], ':name' => $input['name'], ':description' => $input['description']);
    $count = updateData($query, $params);
    if ($count == 0) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// DELETE method
function delete($input) {
    global $db;
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $query = "DELETE FROM إدارة_وصول_المستودعات WHERE id = :id";
    $params = array(':id' => $input['id']);
    $count = deleteData($query, $params);
    if ($count == 0) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}
?>