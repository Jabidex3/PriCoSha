<?php
// Start the session
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";

 $test = $_SESSION["user_name"];
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);


 echo "<h1> Tags Pending: </h1>";
$taggees = $conn->prepare("Select id, username_tagger, status from tag where username_taggee = ? and status = 0");
 $taggees->bind_param('s', $test);
	$taggees->execute();
 $taggees->store_result();
  $taggees->bind_result($tid, $tagger, $status);
 if ($taggees->num_rows > 0) {
	$prev_id = 0;
	echo "<div class = 'tag'>";
	echo "<style> .tag{ background-color:#62567a; text-align:center; width:30%; margin:auto; border-radius:25pt; padding: 5px} </style>";
		while($taggees->fetch() ) {
			if($tid != $prev_id){
				echo "</span>";
			//	echo "<br>";
				echo "<br>";
				echo "<span> <b>Tagged for Content ID: </b> ", $tid;
				$prev_id+=1;
			}
			echo "<br>";
			echo $test, ' tagged by ', $tagger;	
		}
		echo "</span>";
	echo "</div>";
	echo "<hr/>";

} 
else {
	echo "You've been tagged by noone";
}

?>

<html>
	<head>
		<title> Manage Tags </title>
		<style>
			body{ background-color:orange; text-align:center}
		</style>
	</head>
	<body>
		<form  method="post" action="manageTagProcessingAccept.php">
			<h1>Accept or Reject Tags</h1>
			<hr />
			<h2>Accept Tags</h2>
			<label>Content ID: </label>
			<input placeholder="Content ID" name="contentID" type="text" required>
			<br>
			<br>
			<label>Tagger Name: </label>
			<input placeholder="Tagger Username" name="Tagger" type="text" required>
			<br>
			<br>
			<input type="submit" value="Accept">
		</form>
		<br/>
		<br/>
		<br/>
		<br/>
		<form  method="post" action="manageTagProcessingReject.php">
			<h2>Reject Tags</h2>
			<label>Content ID: </label>
			<input placeholder="Content ID" name="contentID" type="text" required>
			<br>
			<br>
			<label>Tagger Name: </label>
			<input placeholder="Tagger Username" name="Tagger" type="text" required>
			<br>
			<br>
			<input type="submit" value="Reject">
		</form>
		<a href="viewInfo.php">Back</a>
		
	</body>
</html>