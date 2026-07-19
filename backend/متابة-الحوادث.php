<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid id']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM متابة_الحوادث WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch();

    // Process output
    if ($result === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    } else {
        http_response_code(200);
        echo json_encode($result);
    }
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $requiredFields = ['name', 'description'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO متابة_الحوادث (name, description) VALUES (:name, :description)');
    $stmt->execute([
        ':name' => filter_var($input['name'], FILTER_SANITIZE_STRING),
        ':description' => filter_var($input['description'], FILTER_SANITIZE_STRING),
    ]);

    // Process output
    http_response_code(201);
    echo json_encode(['message' => 'Record created successfully']);
    exit;
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid id']);
        exit;
    }

    $requiredFields = ['name', 'description'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE متابة_الحوادث SET name = :name, description = :description WHERE id = :id');
    $stmt->execute([
        ':id' => $id,
        ':name' => filter_var($input['name'], FILTER_SANITIZE_STRING),
        ':description' => filter_var($input['description'], FILTER_SANITIZE_STRING),
    ]);

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    } else {
        http_response_code(200);
        echo json_encode(['message' => 'Record updated successfully']);
    }
    exit;
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid id']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM متابة_الحوادث WHERE id = :id');
    $stmt->execute([':id' => $id]);

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    } else {
        http_response_code(200);
        echo json_encode(['message' => 'Record deleted successfully']);
    }
    exit;
}

// Handle invalid request methods
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);