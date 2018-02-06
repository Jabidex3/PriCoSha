<?php
// Start the session
session_start();
 $test = $_SESSION["user_name"];
 
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
?>

<html>
	<head>
		<title> Manage Friend Group </title>
		<style>
			body{ background-color:orange; text-align:center}
			div {margin:auto;text-align:center; background-color:white; color:black; width: 45%; padding:5px}
			b+b{margin-left:5em}
			input[type=text]:focus {background-color: lightblue;}
			label { font-size: 20px }
}
		</style>
	</head>
	<body>
		<?php echo  'Logged in As: ', $test; ?>
		<h1> You are a part of the following Friend Groups: </h1>
		<div>
		<?php
			$partOf = $conn->prepare("select group_name, username_creator from member where username = ? ");
			$partOf->bind_param('s', $test);
				$partOf->execute();
			$partOf->store_result();
			$partOf->bind_result($grname, $uc);
			if ($partOf->num_rows > 0) {
				while($partOf->fetch()) {
					echo "<b>Name of Friend Group: </b> ", $grname, "<b>  Creator: </b>", $uc;
					echo "<br>";
				}
			}
			else{
				echo "You are a part of <b>NO</b> Friend Groups";
			}
			
		?>
		</div>
		<br>
			<form action="manageFGprocessing.php" method="post">
				<label>Which group would you like to manage?</label>
				<br>
				<input type="text" name="fgchosen" placeholder="Name of Friend Group" required>
				<br>
				<input type="submit" value="Submit">
				<br>
			</form>
			<hr>
		<a href="viewInfo.php">Back</a>
		
	</body>
</html>