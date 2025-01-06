<?php
session_start();
include 'db_config.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username']; 
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, role, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['user_id'];  
                $_SESSION['username'] = $user['username'];  
                $_SESSION['role'] = $user['role'];  

                if ($user['role'] === 'Investigator') {
                    header('Location: ../index_investigator.html');
                } else {
                    header('Location: ../index.html');
                }
                exit();
            } else {
                echo "<script>alert('Invalid password');</script>";
            }
        } else {
            echo "<script>alert('User not found');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database query error');</script>";
    }
}

$conn->close();
?>
