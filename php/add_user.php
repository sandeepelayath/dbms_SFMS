<?php
session_start();
require 'config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO user (email, password) VALUES ('$email', '$hashed_password')";
    if (mysqli_query($con, $query)) {
        echo "New user added successfully!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
