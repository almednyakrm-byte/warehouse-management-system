<?php

// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description']) && !isset($input['amount'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input data'));
    exit;
}

// Sanitize input data
$input['name'] = trim($input['name']);
$input['description'] = trim($input['description']);
$input['amount'] = floatval($input['amount']);

// Get PDO instance
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET operation
if (isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM عائدات WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['all'])) {
    $stmt = $db->prepare('SELECT * FROM عائدات');
    $stmt->execute();
    $results = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}

// POST operation
if (isset($input['id'])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
} else {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    $stmt = $db->prepare('INSERT INTO عائدات (name, description, amount) VALUES (:name, :description, :amount)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

// PUT operation
if (isset($input['id'])) {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    $stmt = $db->prepare('UPDATE عائدات SET name = :name, description = :description, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}

// DELETE operation
if (isset($input['id'])) {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    $stmt = $db->prepare('DELETE FROM عائدات WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}

// Close PDO instance
$db = null;