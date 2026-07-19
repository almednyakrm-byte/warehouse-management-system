<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="h-screen bg-gray-700 flex justify-center items-center">
    <div class="glassmorphic-card bg-gray-700/50 backdrop-blur-sm shadow-2xl rounded-3xl p-10">
        <h1 class="text-3xl text-blue-500 font-bold mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-blue-500 text-lg font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="block w-full p-2 rounded-lg bg-gray-700/50 border border-blue-500 text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-blue-500 text-lg font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 rounded-lg bg-gray-700/50 border border-blue-500 text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full p-2 rounded-lg bg-blue-500 text-gray-700 font-bold hover:bg-blue-700 hover:text-blue-500 transition duration-300">Login</button>
        </form>
        <p class="text-blue-500 mt-4">Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-700 transition duration-300">Register here</a></p>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    // Redirect to dashboard or home page
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>