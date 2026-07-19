<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all records
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query to retrieve all records
    $stmt = $pdo->prepare('SELECT * FROM حاويات');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output records as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
}

// Handle POST request to create a new record
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to insert new record
    $stmt = $pdo->prepare('INSERT INTO حاويات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output new record as JSON
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
}

// Handle GET request to retrieve a single record
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($input['id'])) {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!is_numeric($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Prepare SQL query to retrieve single record
    $stmt = $pdo->prepare('SELECT * FROM حاويات WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output record as JSON
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
}

// Handle PUT request to update an existing record
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to update existing record
    $stmt = $pdo->prepare('UPDATE حاويات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output success message as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
}

// Handle DELETE request to delete an existing record
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Prepare SQL query to delete existing record
    $stmt = $pdo->prepare('DELETE FROM حاويات WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Output success message as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
}

// Output error message as JSON for invalid requests
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}