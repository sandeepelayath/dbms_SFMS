<?php 
	session_start();
	ob_start(); 
	require ('config.php');

	$email = $_POST['email'];
	$password = $_POST['password'];

	// Get the user data based on the email
	$result_query = mysqli_query($con, "SELECT * FROM `user` WHERE `email` = '$email'");
	$row = mysqli_fetch_array($result_query);
	$count_query = mysqli_num_rows($result_query);

	// Check if the user exists and compare the hashed passwords
	if ($count_query != 0) {
		// Hash the input password with SHA-256 and compare with the stored password
		if (hash('sha256', $password) === $row['password']) {
			// If password matches, start the session
			$sessionemail = $row['email'];
			$_SESSION['login_user'] = $sessionemail;
			header("Location: ../adminpage.php");
			exit();
		} else {
			// Incorrect password
			echo '<script>alert("Incorrect Credentials Entered"); location.replace(document.referrer);</script>';
		}
	} else {
		// Email not found
		echo '<script>alert("Email not found"); location.replace(document.referrer);</script>';
	}
?>
