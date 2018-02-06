<?php
// Start the session
session_start();
?>

<html>
	<head>
		<title> PriCoSha Friend Group Creation </title>
		<style>
			body{ background-color:orange; text-align:center}
		</style>
	</head>
	<body>
		<form  method="post" action="groupCreationProcessing.php">
			<h1>Create a FriendGroup</h1>
			<hr />
			<label>Group Name: </label>
			<input placeholder="Group Name" name="newGroupName" type="text" required>
			<br>
			<br>
			<label>Description: </label>
			<input placeholder="Description" name="desc" type="text" required>
			<br>
			<br>
			<input type="submit" value="Create">
		</form>
		<a href="viewInfo.php">Back</a>
		
	</body>
</html>