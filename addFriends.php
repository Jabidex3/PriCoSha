<?php
// Start the session
session_start();

   $fgBeingUsed =$_SESSION["friendGroup"];
   $user = $_SESSION["user_name"];
?>

<html>
	<head>
		<title> Add Friends</title>
		<style>
			body{ background-color:orange; text-align:center}
			a+a{margin-left:30px}
		</style>
	</head>
	<body>
		<form  method="post" action="addFriendProcessing.php">
			<h1> Enter first name and last name of person you would like to add to the "<?php echo $fgBeingUsed ?> " chat group</h1>
			<label>First Name: </label>
			<input placeholder="First Name" name="fName" type="text" required>
			<br>
			<br>
			<label>Last Name: </label>
			<input placeholder="Last Name" name="lName" type="text" required>
			<br>
			<br>
			<input type="submit" value="ADD">
		</form>
		<a href="manageOptions.php">Back</a> 
		<a href="viewInfo.php">Home</a>
		
	</body>
</html>