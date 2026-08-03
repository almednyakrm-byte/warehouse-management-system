<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for each operation
$allowedRoles = [
    'GET' => ['user'],
    'POST' => ['user'],
    'PUT' => ['admin'],
    'DELETE' => ['admin']
];

// Check if user has permission for the requested operation
if (isset($input['action']) && $input['action'] != 'GET') {
    if ($_SESSION['role'] != $allowedRoles[$input['action']]) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
}

// Handle GET request
if ($input['action'] == 'GET') {
    try {
        // Prepare SQL query to select all records
        $stmt = $pdo->prepare('SELECT * FROM مستودعات');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return data in JSON format
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        // Return error message if query fails
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle POST request
if ($input['action'] == 'POST') {
    try {
        // Validate input data
        if (!isset($input['name']) || !isset($input['address'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
        
        // Prepare SQL query to insert new record
        $stmt = $pdo->prepare('INSERT INTO مستودعات (name, address) VALUES (:name, :address)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        
        // Return new record ID in JSON format
        header('HTTP/1.1 201 Created');
        header('Content-Type: application/json');
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        // Return error message if query fails
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle PUT request
if ($input['action'] == 'PUT') {
    try {
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['address'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
        
        // Prepare SQL query to update existing record
        $stmt = $pdo->prepare('UPDATE مستودعات SET name = :name, address = :address WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        
        // Return success message in JSON format
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } catch (PDOException $e) {
        // Return error message if query fails
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle DELETE request
if ($input['action'] == 'DELETE') {
    try {
        // Validate input data
        if (!isset($input['id'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare SQL query to delete existing record
        $stmt = $pdo->prepare('DELETE FROM مستودعات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return success message in JSON format
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    } catch (PDOException $e) {
        // Return error message if query fails
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error']);
    }
}
?>