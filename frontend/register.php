<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-gray-100 flex justify-center items-center">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col w-1/3">
        <h2 class="text-3xl text-gray-700 mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <input 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="username" 
                    type="text" 
                    pattern="[A-Za-z\u0600-\u06FF0-9\s]+"
                    required
                >
                <p class="text-red-500 text-xs italic" id="username-error"></p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="email" 
                    type="email" 
                    required
                >
                <p class="text-red-500 text-xs italic" id="email-error"></p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                    id="password" 
                    type="password" 
                    required
                >
                <p class="text-red-500 text-xs italic" id="password-error"></p>
            </div>
            <div class="flex items-center justify-between">
                <button 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    type="submit"
                >
                    Register
                </button>
            </div>
        </form>
        <p class="text-blue-500 text-xs italic mt-4" id="register-success"></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (!username.match(/[A-Za-z\u0600-\u06FF0-9\s]+/)) {
                    $('#username-error').text('Username should only contain letters, numbers and spaces.');
                    return;
                } else {
                    $('#username-error').text('');
                }

                if (!email) {
                    $('#email-error').text('Email is required.');
                    return;
                } else {
                    $('#email-error').text('');
                }

                if (!password) {
                    $('#password-error').text('Password is required.');
                    return;
                } else {
                    $('#password-error').text('');
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response == 'success') {
                            $('#register-success').text('Registration successful. You can now login.');
                            $('#username').val('');
                            $('#email').val('');
                            $('#password').val('');
                        } else {
                            $('#register-success').text('Registration failed. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>