<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is logged in
    if (!$userID) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin' && $method === 'DELETE') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Select all records or specific record
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM إدارة_وصول WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $record = $stmt->fetch();
        if ($record) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    } else {
        $stmt = $pdo->prepare('SELECT * FROM إدارة_وصول');
        $stmt->execute();
        $records = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    }
}

// Handle POST request
if ($method === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if user is logged in
    if (!$userID) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Insert new record
    $stmt = $pdo->prepare('INSERT INTO إدارة_وصول (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request
if ($method === 'PUT') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if user is logged in
    if (!$userID) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Update existing record
    $stmt = $pdo->prepare('UPDATE إدارة_وصول SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is logged in
    if (!$userID) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete existing record
    $stmt = $pdo->prepare('DELETE FROM إدارة_وصول WHERE id = :id');
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}