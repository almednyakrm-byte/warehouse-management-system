<?php
// Start the session to handle user authentication
session_start();

// Import the database connection
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user ID
        echo json_encode(['status' => 'logged_in', 'user_id' => $_SESSION['user_id']]);
    } else {
        // User is not logged in, return a not logged in status
        echo json_encode(['status' => 'not_logged_in']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the action parameter
    if (isset($_POST['action'])) {
        // Handle user registration
        if ($_POST['action'] === 'register') {
            // Check if all required fields are present
            if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
                // Securely check input fields
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                // Check if the username and email are valid
                if (empty($username) || empty($email) || empty($password)) {
                    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
                } else {
                    // Prepare the registration query
                    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
                    $stmt->bind_param('ss', $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the username or email already exists
                    if ($result->num_rows > 0) {
                        echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
                    } else {
                        // Hash the password using password_hash
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Prepare the registration query
                        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                        $stmt->bind_param('sss', $username, $email, $hashed_password);
                        $stmt->execute();

                        // Check if the registration was successful
                        if ($stmt->affected_rows === 1) {
                            echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
                        }
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            }
        } 
        // Handle user login
        elseif ($_POST['action'] === 'login') {
            // Check if all required fields are present
            if (isset($_POST['username'], $_POST['password'])) {
                // Securely check input fields
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $password = $_POST['password'];

                // Check if the username and password are valid
                if (empty($username) || empty($password)) {
                    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
                } else {
                    // Prepare the login query
                    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the username exists
                    if ($result->num_rows === 1) {
                        $user = $result->fetch_assoc();
                        // Verify the password using password_verify
                        if (password_verify($password, $user['password'])) {
                            // Login successful, start a new session
                            $_SESSION['user_id'] = $user['id'];
                            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
                        }
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Invalid username']);
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            }
        } 
        // Handle user logout
        elseif ($_POST['action'] === 'logout') {
            // Unset the user ID from the session
            unset($_SESSION['user_id']);
            echo json_encode(['status' => 'success', 'message' => 'Logout successful']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}