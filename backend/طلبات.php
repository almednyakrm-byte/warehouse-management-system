<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests
if ($method === 'GET') {
    // Validate and sanitize input parameters
    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);
    $limit = isset($params['limit']) ? (int)$params['limit'] : 10;
    $offset = isset($params['offset']) ? (int)$params['offset'] : 0;

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM طلبات ORDER BY id LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST requests
elseif ($method === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO طلبات (name, description, user_id) VALUES (:name, :description, :user_id)');
    $stmt->bindParam(':name', $input['name'], PDO::PARAM_STR);
    $stmt->bindParam(':description', $input['description'], PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Return created resource
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

// Handle PUT requests
elseif ($method === 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE طلبات SET name = :name, description = :description WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':name', $input['name'], PDO::PARAM_STR);
    $stmt->bindParam(':description', $input['description'], PDO::PARAM_STR);
    $stmt->bindParam(':id', $input['id'], PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Return updated resource
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Resource updated successfully'));
}

// Handle DELETE requests
elseif ($method === 'DELETE') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM طلبات WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $input['id'], PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Return deleted resource
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Resource deleted successfully'));
}

// Return error for unsupported methods
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}