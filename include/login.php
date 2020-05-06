<?php

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

    // Variables
    $login_url = "../login.php";
    $welcome_page = "../index.php";
    
    // Database connection
    require_once 'dbh.php';

    // Get user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate User Input
    if(empty($username) || empty($password)) {
        header("Location:{$login_url}?error=emptyfield");
        exit();
    }

    // Get the user credentials
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);

    if($stmt->rowCount() > 0) {
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $password_check = password_verify($password, $row["password"]);
            $username_check = $row["username"];
    
            if($username === $username_check && $password_check === true) {
                session_start();
                $_SESSION["username"] = $row["username"];
                $_SESSION["user_id"] = $row["user_id"];
                header("Location:{$welcome_page}?session=success");
                exit();
    
            } else {
                header("Location:{$login_url}?error=auth");
                exit();
            }
        }

    } else {
        header("Location:{$login_url}?error=auth");
            exit();
    }

} else {
    header("Location:{$login_url}");
    exit();
}

?>