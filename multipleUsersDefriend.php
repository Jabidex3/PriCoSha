<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $user = $_SESSION["user_name"];
  $fgBeingUsed = $_SESSION["friendGroup"];
  $_SESSION["unameChosenRemoved"]= $_POST['removeuname'];
//  $f =  $_SESSION["fname"];
 // $l =  $_SESSION["lname"];
  
  if (empty($_SESSION["unameChosenRemoved"])){
	echo "Empty username";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='deFriend.php'>Retry</a>";
  }
  elseif(strlen($_SESSION["unameChosenRemoved"]) > 50){
	echo "invalid username";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='deFriend.php'>Retry</a>";
  }
  else
  {
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	//check if person you are removing is actually in the group
	$validStatement =  $conn->prepare("Select username from member where group_name = ? and username_creator = ? and username = ?");
	$validStatement->bind_param('sss', $fgBeingUsed , $user, $_SESSION["unameChosenRemoved"]);
		$validStatement->execute();
	$validStatement->store_result();
	if ($validStatement->num_rows > 0)
	{
		if($_SESSION["unameChosenRemoved"] == $user){
			echo "Friend Removal Unsuccessful. Creator cannot remove themselves from friend group that they created";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='manageOptions.php'>Back to friend group options</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Home</a>";
		}
		else{
			$deleteStatement = $conn->prepare("Delete from member where group_name = ? and username_creator = ? and username = ?");
			$deleteStatement ->bind_param('sss', $fgBeingUsed , $user, $_SESSION["unameChosenRemoved"]);
			if($deleteStatement ->execute()){
				echo "<b>Friend Removal Successful.</b>";
				echo "<br>";
				echo "You have removed <b> ", $_SESSION["unameChosenRemoved"] , "</b> from the <b>",  $fgBeingUsed, "</b> friend group";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='manageOptions.php'>Back to friend group options</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Home</a>";
			}
		}
	}
	else
	{		
			echo "invalid username entered";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='deFriend.php'>try again </a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Home</a>";
		
	}
  }

?>