<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $companyId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    // Check if company ID is valid
    if (!$companyId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid company ID']);
        exit;
    }

    // Prepare SQL query to select company
    $stmt = $pdo->prepare('SELECT * FROM الشركات WHERE id = :id');
    $stmt->bindParam(':id', $companyId);
    $stmt->execute();

    // Fetch company data
    $company = $stmt->fetch();

    // Check if company exists
    if (!$company) {
        http_response_code(404);
        echo json_encode(['error' => 'Company not found']);
        exit;
    }

    // Return company data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($company);
}

// Handle POST request
elseif ($method === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query to insert company
    $stmt = $pdo->prepare('INSERT INTO الشركات (name, address, phone) VALUES (:name, :address, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Get inserted company ID
    $companyId = $pdo->lastInsertId();

    // Return inserted company ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $companyId]);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $companyId = filter_var($input['id'], FILTER_VALIDATE_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_STRING);

    // Check if company ID is valid
    if (!$companyId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid company ID']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query to update company
    $stmt = $pdo->prepare('UPDATE الشركات SET name = :name, address = :address, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $companyId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Company not found']);
        exit;
    }

    // Return updated company ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $companyId]);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Validate and sanitize input
    $companyId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    // Check if company ID is valid
    if (!$companyId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid company ID']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query to delete company
    $stmt = $pdo->prepare('DELETE FROM الشركات WHERE id = :id');
    $stmt->bindParam(':id', $companyId);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Company not found']);
        exit;
    }

    // Return deleted company ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $companyId]);
}

// Return error for invalid request method
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}