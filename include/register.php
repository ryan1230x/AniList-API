<?php

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {

    // Variables
    $register_url = "../register";
    $login_url = "../login";  
    $welcome_page = "/";

    // Database connection
    include_once 'dbh.php';

    // Get User Input
    $username = $_POST["username"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];

    // Sanitize user Input
    $username = $_POST["username"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    
    //Sanitize user input
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password1 = filter_var($password1, FILTER_SANITIZE_STRING);
    $password2 = filter_var($password2, FILTER_SANITIZE_STRING);
    
    // Validate User Input
    if(empty($username) || empty($password1) || empty($password2)){
        header("Location:{$register_url}?error=emptyfields");
        exit();
    }

    if($password1 !== $password2) {
        header("Location:{$register_url}?error=match");
        exit();
    }
    
    // Check if the username already exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);

    if($stmt->rowCount > 0) {
        header("Location:{$register_url}?error=user");
        exit();
    }

    // Insert into the database
    $query = "INSERT INTO users(username, password, user_id) VALUES(?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    // Create user_id
    $salt1 = md5('€¶“adŧłđ');
    $salt2 = md5('`++çñ|@æłĸ');
    $username_hash = sha1($username);
    $user_id = $salt1 . $username_hash . $salt2;

    // Hash user password    
    $password_hash = password_hash($password2, PASSWORD_DEFAULT);
    $stmt->execute([$username, $password_hash, $user_id]);

    //Log the user in    
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);

    if($stmt->rowCount() > 0) {
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $password_check = password_verify($password1, $row["password"]);
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

}



?>