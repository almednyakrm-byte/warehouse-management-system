<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/materials' => array('GET', 'POST'),
    '/materials/:id' => array('GET', 'PUT', 'DELETE')
);

// Get route and method
$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Validate route and method
if (!isset($routes[$route])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Get allowed methods for route
$allowedMethods = $routes[$route];

// Check if method is allowed
if (!in_array($method, $allowedMethods)) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Get material ID from route
$materialId = null;
if (strpos($route, ':id') !== false) {
    $materialId = (int) explode(':id', $route)[1];
}

// Validate input data
if ($method === 'POST') {
    // Validate material data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Handle GET request
    if ($method === 'GET') {
        if ($materialId) {
            // Get material by ID
            $stmt = $db->prepare('SELECT * FROM materials WHERE id = :id');
            $stmt->bindParam(':id', $materialId);
            $stmt->execute();
            $material = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($material) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($material);
            } else {
                http_response_code(404);
                echo json_encode(array('error' => 'Material not found'));
            }
        } else {
            // Get all materials
            $stmt = $db->prepare('SELECT * FROM materials');
            $stmt->execute();
            $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($materials);
        }
    }

    // Handle POST request
    elseif ($method === 'POST') {
        // Validate material data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Insert material
        $stmt = $db->prepare('INSERT INTO materials (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Material created successfully'));
    }

    // Handle PUT request
    elseif ($method === 'PUT') {
        // Validate material data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Update material
        $stmt = $db->prepare('UPDATE materials SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $materialId);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Material updated successfully'));
    }

    // Handle DELETE request
    elseif ($method === 'DELETE') {
        // Delete material
        $stmt = $db->prepare('DELETE FROM materials WHERE id = :id');
        $stmt->bindParam(':id', $materialId);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Material deleted successfully'));
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => $e->getMessage()));
}