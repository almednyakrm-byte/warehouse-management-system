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

// Handle GET request
if ($method === 'GET') {
    // Get storage management data
    $stmt = $pdo->prepare('SELECT * FROM storage_management');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return storage management data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['storage_name']) || !isset($inputData['storage_capacity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $storageName = htmlspecialchars($inputData['storage_name']);
    $storageCapacity = htmlspecialchars($inputData['storage_capacity']);

    // Insert new storage management data
    $stmt = $pdo->prepare('INSERT INTO storage_management (storage_name, storage_capacity) VALUES (:storage_name, :storage_capacity)');
    $stmt->bindParam(':storage_name', $storageName);
    $stmt->bindParam(':storage_capacity', $storageCapacity);
    $stmt->execute();

    // Return new storage management data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('storage_name' => $storageName, 'storage_capacity' => $storageCapacity));
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['storage_id']) || !isset($inputData['storage_name']) || !isset($inputData['storage_capacity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $storageID = htmlspecialchars($inputData['storage_id']);
    $storageName = htmlspecialchars($inputData['storage_name']);
    $storageCapacity = htmlspecialchars($inputData['storage_capacity']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update existing storage management data
    $stmt = $pdo->prepare('UPDATE storage_management SET storage_name = :storage_name, storage_capacity = :storage_capacity WHERE storage_id = :storage_id');
    $stmt->bindParam(':storage_id', $storageID);
    $stmt->bindParam(':storage_name', $storageName);
    $stmt->bindParam(':storage_capacity', $storageCapacity);
    $stmt->execute();

    // Return updated storage management data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('storage_name' => $storageName, 'storage_capacity' => $storageCapacity));
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['storage_id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $storageID = htmlspecialchars($inputData['storage_id']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete storage management data
    $stmt = $pdo->prepare('DELETE FROM storage_management WHERE storage_id = :storage_id');
    $stmt->bindParam(':storage_id', $storageID);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Storage management data deleted successfully'));
    exit;
}

// Return error message
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;