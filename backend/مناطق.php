<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Forbidden access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        // Prepare select query
        $stmt = $pdo->prepare("SELECT * FROM مناطق");
        $stmt->execute();
        $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return regions
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($regions);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        // Prepare select query
        $stmt = $pdo->prepare("SELECT * FROM مناطق WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $region = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return region
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($region);
    } catch (PDOException $e) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Region not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_by_name') {
    try {
        // Prepare select query
        $stmt = $pdo->prepare("SELECT * FROM مناطق WHERE name = :name");
        $stmt->bindParam(':name', $_GET['name']);
        $stmt->execute();
        $region = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return region
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($region);
    } catch (PDOException $e) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Region not found'));
    }
}

// Handle POST request
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    try {
        // Validate input
        if (!isset($input['name']) || empty($input['name'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        
        // Prepare insert query
        $stmt = $pdo->prepare("INSERT INTO مناطق (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        // Return created region
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle PUT request
if (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    try {
        // Validate input
        if (!isset($input['id']) || !isset($input['name']) || empty($input['name'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        
        // Prepare update query
        $stmt = $pdo->prepare("UPDATE مناطق SET name = :name WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        // Return updated region
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Region updated successfully'));
    } catch (PDOException $e) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Region not found'));
    }
}

// Handle DELETE request
if (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    try {
        // Validate input
        if (!isset($input['id']) || empty($input['id'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare delete query
        $stmt = $pdo->prepare("DELETE FROM مناطق WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return deleted region
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Region deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Region not found'));
    }
}

?>