<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $user = $_SESSION["user_name"];
  $fgBeingUsed = $_SESSION["friendGroup"];
  $_SESSION["unameChosen"]= $_POST['adduname'];
//  $f =  $_SESSION["fname"];
 // $l =  $_SESSION["lname"];
  
  if (empty($_SESSION["unameChosen"])){
	echo "Empty username";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='addFriends.php'>Retry</a>";
  }
  elseif(strlen($_SESSION["unameChosen"]) > 50){
	echo "invalid username";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='addFriends.php'>Retry</a>";
  }
  else{
   $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  //getting creator of friendgroup that you are in
		$cstmt = $conn->prepare("Select username_creator from member where group_name = ? and username = ? ");
		$cstmt->bind_param('ss', $fgBeingUsed ,$user);
			$cstmt->execute();
		$cstmt->store_result();
		$cstmt->bind_result($creator);
		 if ($cstmt->num_rows == 1) 
		 {
			// output data of each row
			while($cstmt->fetch()) {
				//echo $creator;
			}
		}
		
		//check if person you are adding is already in a group with same name   
			//check if person you are adding is already in the group
	$validStatement =  $conn->prepare("Select username from member where group_name = ? and username = ?");
			$validStatement->bind_param('ss', $fgBeingUsed , $_SESSION["unameChosen"]);
				$validStatement->execute();
			$validStatement->store_result();
			//$validStatement->bind_result($newMember)
			if ($validStatement->num_rows > 0)
			{
				echo "Unfortunately, the user is already part of a friend group with the same name.";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='addFriends.php'>Return To Add friend</a>";
			}
			else{
				$stmt2 = $conn->prepare("INSERT INTO member(username, group_name, username_creator) VALUES(?, ?, ?)");	
				$stmt2->bind_param("sss",   $_SESSION["unameChosen"], $fgBeingUsed, $creator);
				if ($stmt2->execute()) {
					echo "Congratulations you have added <b>", $_SESSION["fname"]," ", $_SESSION["lname"], "</b> successfully";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='manageFG.php'>Return To Manage Friend Group</a>";
				}
  }
  }

?>