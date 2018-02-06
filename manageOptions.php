<?php
// Start the session
session_start();
  $fgNow = $_SESSION["friendGroup"];
   $test = $_SESSION["user_name"];
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
?>

<html>
	<head>
		<title> Manage Options </title>
		<style>
			body{ background-color:orange; text-align:center}
			div {margin:auto;text-align:center; background-color:white; color:black; width: 45%; padding:5px}
			b+b{margin-left:5em}
			input[type=text]:focus {background-color: lightblue;}
			label { font-size: 20px }
			a{
				background-color: black;
				color: white;
				padding: 10px 10px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				
			}

			a:hover, a:active {
				background-color: white;
				color: black;
			}
			a+a{margin-left:15px}
		</style>
	</head>
	<body>
		<?php echo  'Logged in As: ', $test; ?>
		<h1> Members of the "<?php echo  $_SESSION["friendGroup"] ?>" Friend Group: </h1>
		<div>
		<?php
			$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
			//getting creator of friendgroup that you are in
			$cstmt = $conn->prepare("Select username_creator from member where group_name = ? and username = ? ");
			$cstmt->bind_param('ss', $fgNow  ,$test);
				$cstmt->execute();
			$cstmt->store_result();
			$cstmt->bind_result($creator);
			if ($cstmt->num_rows == 1) 
			{
				$cstmt->fetch();
			}
		
			$partOf = $conn->prepare("select username, first_name, last_name from member natural join person where username_creator = ? and group_name = ?");
			$partOf->bind_param('ss', $creator, $fgNow);
				$partOf->execute();
			$partOf->store_result();
			$partOf->bind_result($member, $f, $l);
			if($partOf->num_rows ==1){
				echo "You are the only person in the friend group :( ";
			}
			elseif ($partOf->num_rows > 1) {
				while($partOf->fetch()) {
					echo "<b>Username: </b> ", $member, " <b>First Name:</b> ", $f, " <b>Last Name:</b> ", $l ;
					echo "<br>";
				}
			}
			else{
				echo "Unexpected Error. No one is a part of this friend group";
			}
		?>
		</div>
		<br>
			<a href='addFriends.php'>Add Friend to Group</a>
			<a href='deFriend.php'>De-friend/Remove friend from group</a>
			<hr>
		<a href="manageFG.php">Back</a>
		
	</body>
</html>