<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
  $_SESSION["friendGroup"]= $_POST['fgchosen'];
   $test = $_SESSION["user_name"];
   
   if (empty($_SESSION["friendGroup"]))
   {
	echo "No friend group chosen";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='manageFG.php'>Try Again</a>";
  }
   else{
	   $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
			$stmt = $conn->prepare("select group_name from member where username = ? and group_name = ?");
			$stmt->bind_param('ss', $test, $_SESSION["friendGroup"] );
				$stmt->execute();
			$stmt->store_result();
			//$stmt->bind_result($grname);
			if ($stmt->num_rows > 0) {
				//header("Location:addOrRemoveFriends.php");
				header("Location:manageOptions.php");
			}
			else{
				echo "You are not part of any friend group called: ",  $_SESSION["friendGroup"];
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='manageFG.php'>enter another group name here</a>";
			}
	}
  

?>