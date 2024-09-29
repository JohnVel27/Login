<?php
session_start();
require('./dbConfig.php');

$message = ''; // Initialize message variable

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the user is already logged in
    if (isset($_SESSION['logged_in_user'])) {
        $message = "Username is already logged in. Wait for him to log out first.";
    } else {
        if (empty($username) || empty($password)) {
            echo "<script>alert('Please fill up all fields')</script>";
        } else {
            // Retrieve the hashed password from the database
            $query = "SELECT * FROM login WHERE username = :username";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashedPassword = $row['password'];

                // Verify the entered password with the hashed password
                if (password_verify($password, $hashedPassword)) {
                    // Log the user in
                    $_SESSION['logged_in_user'] = $username;
                    $message = "User logged in: " . htmlspecialchars($username). "<br>Hashed Password: " . htmlspecialchars($hashedPassword);
                } else {
                    $message = 'Invalid Credential';
                }
            } else {
                $message = 'No user found with that username';
            }
        }
    }
}

if (isset($_POST['logout'])) {
    // On logout, clear the session
    unset($_SESSION['logged_in_user']);
    $message = "You have logged out.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Flexbox container for centering the form */
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center; /* Center the content horizontally */
        }

        form {
            width: 100%;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px 0;
        }

        /* Style for the login button */
        input[name="login"] {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        input[name="login"]:hover {
            background-color: #0056b3;
        }

        /* Style for the logout button (red) */
        input[name="logout"] {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            margin-top: 30px; /* Adds 30px space above the logout button */
        }

        input[name="logout"]:hover {
            background-color: #cc0000;
        }

        /* Style for the message */
        .message {
            margin-top: 20px; /* Adds 20px space between the message and logout button */
            text-align: center; /* Center the text */
            color: #333;
            font-size: 16px;
            max-width: 100%; /* Ensure it fits inside the container */
            word-wrap: break-word; /* Break long words if necessary */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login form -->
        <form action="" method="post">
            <h1>Login</h1>
            <input type="text" name="username" placeholder="Enter your username" required />
            <input type="password" name="password" placeholder="Enter your password" required />
            <input type="submit" name="login" value="Login" />
        </form>

        <!-- Logout form (with 30px space above it) -->
        <form action="" method="post">
            <input type="submit" name="logout" value="Logout" />
        </form>

        <!-- Message displayed below the logout button -->
        <div class="message">
            <?php echo $message; ?>
        </div>
    </div>
</body>
</html>
