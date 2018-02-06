<?php
// Start the session
session_start();
?>

<html>
	<head>
		<title> PriCoSha Registration </title>
		<style>
			body{ background-color:orange; text-align:center}
		</style>
	</head>
	<body>
		<form  method="post" action="registrationConnection.php">
			<h1>Create an Account</h1>
			<hr />
			<label>First Name: </label>
			<input placeholder="First Name" name="loginFirstname" type="text" required>
			<br>
			<br>
			<label>Last Name: </label>
			<input placeholder="Last Name" name="loginLastname" type="text" required>
			<br>
			<br>
			<label>Username: </label>
			<input placeholder="Username" name="loginUsername" type="text" required>
			<br>
			<br>
			<label>Password: </label>
			<input placeholder="Password" name="loginPassword" type="text" required>
			<br>
			<br>
			<input type="submit" value="Register">
		</form>
		<a href="index.php">Back</a>
		
	</body>
</html>