<?php
session_start();
include 'db_config.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];


    var_dump($_POST); 

    $username = trim($username);
    $email = trim($email);

    if (empty($password)) {
        echo "<script>alert('Password is required');</script>";
        exit();
    }

    if (!empty($email)) {
        $check_sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('User with this email already exists');</script>";
            $stmt->close();
            $conn->close();
            exit();
        }

        $stmt->close();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href = '../login.html';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
