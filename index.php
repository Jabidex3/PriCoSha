<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title> Welcome to PriCoSha! </title>
	<style>
		body{ background-color:orange; text-align:center}	
		div {background-color:black;color:orange; font-family: "Times New Roman", Times, serif; font-style: italic; font-size: 40px; font-weight: bold; text-align:center}
		form{border-style: solid; width:50%; margin:auto; padding:10px; border-radius: 25px; }
		input[type=text]:focus {background-color: lightblue;}
	</style>
</head>
<body>
	<div> PriCoSha </div>
	<br>
	<br>
	<form action="connection.php" method="post">
        <label>Username: </label>
		<input type="text" name="u_name" placeholder="Username" required>
        <br>
        <label>Password:</label>  
		<input type="text" name="u_password" placeholder="Password" required>
		<br>      
		<br>
		<hr>
        <input type="submit" value="Submit">
		<br>
        <a href="Register.php">Register a New Account</a></p>
    </form>
</body>
</html>