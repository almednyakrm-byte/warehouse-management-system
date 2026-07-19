<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = ($user_role == 'admin');

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin to allow edit and delete operations
    if ($is_admin) {
        $stmt = $pdo->prepare('SELECT * FROM إدارة_المبيعات');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM إدارة_المبيعات WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($input_data['name']);
    $description = htmlspecialchars($input_data['description']);

    // Insert data into database
    $stmt = $pdo->prepare('INSERT INTO إدارة_المبيعات (name, description, user_id) VALUES (:name, :description, :user_id)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data inserted successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin to allow edit operations
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input_data['id']);
    $name = htmlspecialchars($input_data['name']);
    $description = htmlspecialchars($input_data['description']);

    // Update data in database
    $stmt = $pdo->prepare('UPDATE إدارة_المبيعات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data updated successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin to allow delete operations
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input_data['id']);

    // Delete data from database
    $stmt = $pdo->prepare('DELETE FROM إدارة_المبيعات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data deleted successfully'));
}