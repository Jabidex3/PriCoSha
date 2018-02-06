<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $user = $_SESSION["user_name"];
 $fgBeingUsed = $_SESSION["friendGroup"];
 $_SESSION["fname"]= $_POST['fName'];
 $_SESSION["lname"] = $_POST['lName'];
 
  if (empty($_SESSION["fname"])){
	echo "Empty First Name";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='deFriend.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["fname"]) > 50){
	echo "First Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='deFriend.php'>Try Again</a>";
  }
  elseif (empty($_SESSION["lname"])){
	echo "Empty Last Name";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='deFriend.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["lname"]) > 50){
	echo "Last Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='deFriend.php'>Try Again</a>";
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
			$cstmt->fetch();
		}
		
		//check if user is creator
		if ($creator == $user)
		{
			//check if person you are removing is real
			$stmt = $conn->prepare("Select username from person where first_name = ? and last_name = ?");
			$stmt->bind_param('ss',  $_SESSION["fname"],  $_SESSION["lname"]);
				$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($newMember);

			if ($stmt->num_rows == 1) //only 1 person with the fname and last name
			{ 
				$stmt->fetch();
				//echo $newMember;
				//check if person you are removing is actually in the group
				$validStatement =  $conn->prepare("Select username from member where group_name = ? and username_creator = ? and username = ?");
				$validStatement->bind_param('sss', $fgBeingUsed , $creator, $newMember);
					$validStatement->execute();
				$validStatement->store_result();
				if ($validStatement->num_rows > 0)
				{
					if($newMember == $creator){
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
						$deleteStatement ->bind_param('sss', $fgBeingUsed , $creator, $newMember);
						if($deleteStatement ->execute()){
							echo "<b>Friend Removal Successful.</b>";
							echo "<br>";
							echo "You have removed <b> ", $newMember , "</b> from the <b>",  $fgBeingUsed, "</b> friend group";
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
						echo "There is no user with that first name and last name that is part of the group ";
						echo "<html>";
						echo "<style> body{ background-color:orange; text-align:center}	</style>";
						echo "<br>";
						echo"<a href='deFriend.php'>Back to Remove a friend </a>";
						echo "<br>";
						echo"<a href='viewInfo.php'>Home</a>";
					
				}
			}
			elseif($stmt->num_rows > 1)
			{ //more than 1 person with same fname and lname in db
				
				//check if person you are removing is actually in the group
				$validStatement =  $conn->prepare("Select username from member natural join person where group_name = ? and username_creator = ? and first_name = ? and last_name = ?");
				$validStatement->bind_param('ssss', $fgBeingUsed , $creator, $_SESSION["fname"], $_SESSION["lname"]);
					$validStatement->execute();
				$validStatement->store_result();
				$validStatement->bind_result($memInGroup);
				if ($validStatement->num_rows == 1) //1 person in group with given fname and lname
				{
					$validStatement->fetch();
					if($memInGroup == $creator){
						echo "Friend Removal Unsuccessful. Creator cannot remove themselves from friend group that they created";
						echo "<html>";
						echo "<style> body{ background-color:orange; text-align:center}	</style>";
						echo "<br>";
						echo"<a href='manageOptions.php'>Back to friend group options</a>";
						echo "<br>";
						echo"<a href='viewInfo.php'>Home</a>";
					}
					else{
						$validStatement->fetch();
						$deleteStatement = $conn->prepare("Delete from member where group_name = ? and username_creator = ? and username = ?");
						$deleteStatement ->bind_param('sss', $fgBeingUsed , $creator, $memInGroup);
						if($deleteStatement ->execute()){
							echo "<b>Friend Removal Successful.</b>";
							echo "<br>";
							echo "You have removed <b> ", $memInGroup , "</b> from the <b>",  $fgBeingUsed, "</b> friend group";
							echo "<html>";
							echo "<style> body{ background-color:orange; text-align:center}	</style>";
							echo "<br>";
							echo"<a href='manageOptions.php'>Back to friend group options</a>";
							echo "<br>";
							echo"<a href='viewInfo.php'>Home</a>";
						}
					}
				}
				elseif($validStatement->num_rows > 1) //more than 1 person in group with same name
				{
					//---
					echo "There are more than 1 person with the same name in the group. Specify which username is correct.";
					echo "<br>";
					while($validStatement->fetch()) {
						echo "<b>username:</b>	", $memInGroup, "<b> First Name: </b>", $_SESSION["fname"] ,"<b> Last Name: </b>", $_SESSION["lname"] ;
						echo "<br>";
					}
					
					echo "<form  method='post' action='multipleUsersDefriend.php'>";
					echo "<label>Username: </label>";
					echo "<input placeholder='username' name='removeuname' type='text' required>";
					echo "<br>";
					echo "<input type='submit' value='Remove'>";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
			//---
				}				
				else
				{		
						echo "There is no user with that first name and last name that is part of the group ";
						echo "<html>";
						echo "<style> body{ background-color:orange; text-align:center}	</style>";
						echo "<br>";
						echo"<a href='deFriend.php'>Back to Remove a friend </a>";
						echo "<br>";
						echo"<a href='viewInfo.php'>Home</a>";
					
				}
				

			}
			else
			{ //no matches
				echo "Unfortunately, there is no one named <b>", $_SESSION["fname"]," ", $_SESSION["lname"], "</b> that is part of this group. Perhaps you have typed in the wrong name.";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='deFriend.php'>Try Again</a>";
				echo "<br>";
				echo"<a href='manageFG.php'>Return To Manage Friend Group</a>";
			}
		}
		else
		{
			echo "You are not the creator of the friend group. Only the creator may remove a person from the group.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='manageOptions.php'>Back to friend group options</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Home</a>";
		}
		
		
		
		
  }
 
 ?>