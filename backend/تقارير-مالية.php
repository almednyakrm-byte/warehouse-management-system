<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Process GET requests
if ($method === 'GET') {
    // Validate and sanitize input parameters
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is admin for specific ID
    if ($id && $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $sql = 'SELECT * FROM تقارير_مالية';
    if ($id) {
        $sql .= ' WHERE id = :id';
    }

    // Prepare and execute PDO statement
    $stmt = $pdo->prepare($sql);
    if ($id) {
        $stmt->bindParam(':id', $id);
    }
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Process POST requests
elseif ($method === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $required_fields = ['field1', 'field2']; // Replace with actual field names
    foreach ($required_fields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare SQL query
    $sql = 'INSERT INTO تقارير_مالية (field1, field2) VALUES (:field1, :field2)';

    // Prepare and execute PDO statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':field1', $input['field1']);
    $stmt->bindParam(':field2', $input['field2']);
    $stmt->execute();

    // Return created ID
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Process PUT requests
elseif ($method === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $required_fields = ['id', 'field1', 'field2']; // Replace with actual field names
    foreach ($required_fields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare SQL query
    $sql = 'UPDATE تقارير_مالية SET field1 = :field1, field2 = :field2 WHERE id = :id';

    // Prepare and execute PDO statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':field1', $input['field1']);
    $stmt->bindParam(':field2', $input['field2']);
    $stmt->execute();

    // Return updated ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $input['id']]);
}

// Process DELETE requests
elseif ($method === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required field: id']);
        exit;
    }

    // Prepare SQL query
    $sql = 'DELETE FROM تقارير_مالية WHERE id = :id';

    // Prepare and execute PDO statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Return deleted ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $input['id']]);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}