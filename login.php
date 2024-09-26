<?php
session_start();
require('./dbConfig.php');

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

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
                $_SESSION['username'] = $username;
                header('Location: ordermanagement.php');
                exit();
            } else {
                echo 'Invalid Credential';
            }
        } else {
            header('Location: login.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=!, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login </h1>
    <form action="/tutorial/login.php" method="post">
        <input type="text" name="username" placeholder="Enter your username"/> <br><br>
        <input type="password" name="password" placeholder="Enter your password"/> <br><br>
        <input type="submit" name="login" value="Login"/>
        <p><a href="register.php">Register Account</a></p>
    </form>
</body>
</html>
