<?php
// Start the session
session_start();
?>

<html>
	<head>
		<title> Delete Content </title>
		<style>
			body{ background-color:orange; text-align:center}
		</style>
	</head>
	<body>
		<form  method="post" action="deleteContentProcessing.php">
			<h1>Delete Content</h1>
			<hr />
			<label>Content ID: </label>
			<input placeholder="Content ID" name="deleteContent" type="text" required>
			<br>
			<br>
			<input type="submit" value="Delete">
		</form>
		<a href="viewInfo.php">Back</a>
		
	</body>
</html>