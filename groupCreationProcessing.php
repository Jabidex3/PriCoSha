<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $gname = $_POST['newGroupName'];
 $desc = $_POST['desc'];
 
 $_SESSION["gname"] = $gname;
 $_SESSION["gdesc"] = $desc;
 $test = $_SESSION["user_name"];
 
  if (empty($_SESSION["gname"])){
	echo "Empty Group Name";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='groupCreation.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["gname"]) > 50){
	echo "Group Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='groupCreation.php'>Try Again</a>";
  }
  elseif (empty($_SESSION["gdesc"])){
	echo "Empty Group Description";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='groupCreation.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["gdesc"]) > 50){
	echo "Group Description is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='groupCreation.php'>Try Again</a>";
  }
  else{
	 // Create connection
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	$stmt = $conn->prepare("Select group_name from friendgroup where group_name = ? and username = ?");
	$stmt->bind_param('ss', $_SESSION["gname"], $test);
		$stmt->execute();
	$stmt->store_result();
		if ($stmt->num_rows == 1){
			echo "You have already created a group with the same groupname!";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='groupCreation.php'>Try Again</a>";
		}
		else{
			$stmt1 = $conn->prepare("INSERT INTO friendgroup (group_name, username, description) VALUES(?, ?, ?)");	
			$stmt1->bind_param("sss",  $_SESSION["gname"],  $test,  $_SESSION["gdesc"]);
			if ($stmt1->execute()) {
				$stmt2 = $conn->prepare("INSERT INTO member(username, group_name, username_creator) VALUES(?, ?, ?)");	
				$stmt2->bind_param("sss",  $test, $_SESSION["gname"],  $test);
				if ($stmt2->execute()) {
					echo "Congratulations! You have successfully created the friendgroup";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='manageFG.php'>Click Here</a>";
				}
			}
			else{
				echo "Unexpected Error";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='groupCreation.php'>Try Again</a>";
			}
		}
  
  }
  
 ?>