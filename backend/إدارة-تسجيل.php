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

// Helper function to get user role
function get_user_role() {
    global $_SESSION;
    return $_SESSION['user_role'];
}

// Helper function to validate input
function validate_input($input) {
    // Add validation rules here
    return true;
}

// Helper function to sanitize input
function sanitize_input($input) {
    // Add sanitization rules here
    return $input;
}

// GET operation
function get($input) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM إدارة_تسجيل');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// CREATE operation
function create($input) {
    global $pdo;
    if (!validate_input($input)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        return;
    }
    $input = sanitize_input($input);
    $stmt = $pdo->prepare('INSERT INTO إدارة_تسجيل SET ?');
    $stmt->execute($input);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

// UPDATE operation (admin-only)
function update($input) {
    global $pdo;
    if (!validate_input($input)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        return;
    }
    $input = sanitize_input($input);
    if (get_user_role() !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    $id = $input['id'];
    $stmt = $pdo->prepare('UPDATE إدارة_تسجيل SET ? WHERE id = ?');
    $stmt->execute(array_merge($input, array('id' => $id)));
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// DELETE operation (admin-only)
function delete($input) {
    global $pdo;
    if (!validate_input($input)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        return;
    }
    $id = $input['id'];
    if (get_user_role() !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    $stmt = $pdo->prepare('DELETE FROM إدارة_تسجيل WHERE id = ?');
    $stmt->execute(array($id));
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}