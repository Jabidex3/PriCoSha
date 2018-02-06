<?php
// Start the session
session_start();
?>

<html>
	<head>
		<title> Add Tags </title>
		<style>
			body{ background-color:orange; text-align:center}
		</style>
	</head>
	<body>
		<form  method="post" action="tagContentProcessing.php">
			<h1>Tag Content</h1>
			<hr />
			<label>Content ID: </label>
			<input placeholder="Content ID" name="tagContent" type="text" required>
			<br>
			<br>
			<label>Person you wish to tag: </label>
			<input placeholder="Taggee Username" name="taggeeName" type="text" required>
			<br>
			<br>
			<input type="submit" value="Tag">
		</form>
		<a href="viewInfo.php">Back</a>
		
	</body>
</html>