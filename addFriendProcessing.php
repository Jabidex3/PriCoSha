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
	echo"<a href='addFriends.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["fname"]) > 50){
	echo "First Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='addFriends.php'>Try Again</a>";
  }
  elseif (empty($_SESSION["lname"])){
	echo "Empty Last Name";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='addFriends.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["lname"]) > 50){
	echo "Last Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='addFriends.php'>Try Again</a>";
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
	///////////////
	if($user == $creator)
	{
		//check if person you are adding is real
		$stmt = $conn->prepare("Select username from person where first_name = ? and last_name = ?");
		$stmt->bind_param('ss',  $_SESSION["fname"],  $_SESSION["lname"]);
			$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($newMember);
		//echo $newMember;
		if ($stmt->num_rows == 1) //only 1 person with the fname and last name
		{ 
		  while($stmt->fetch()) {
			//echo $newMember;
			}
			
			//check if person you are adding is already in a group with same name   
			//check if person you are adding is already in the group
			
			$validStatement =  $conn->prepare("Select username from member where group_name = ? and username = ?");
			$validStatement->bind_param('ss', $fgBeingUsed , $newMember);
				$validStatement->execute();
			$validStatement->store_result();
			if ($validStatement->num_rows > 0)
			{
				echo "Unfortunately, the user is already part of a friend group with the same name.";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='addFriends.php'>Try Again</a>";
					echo "<br>";
					echo"<a href='manageFG.php'>Return To Manage Friend Group</a>";
			}
			else{
				$stmt2 = $conn->prepare("INSERT INTO member(username, group_name, username_creator) VALUES(?, ?, ?)");	
				$stmt2->bind_param("sss",  $newMember, $fgBeingUsed, $creator);
				if ($stmt2->execute()) {
					echo "Congratulations you have added <b>", $_SESSION["fname"]," ", $_SESSION["lname"], "</b> successfully";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='manageFG.php'>Return To Manage Friend Group</a>";
				}
			}
		}
		elseif($stmt->num_rows > 1){ //more than 1 person with same fname and lname
		
				echo "There are more than 1 person with the same name. Specify which username is correct.";
				echo "<br>";
				while($stmt->fetch()) {
					echo "<b>username:</b>	", $newMember, "<b> First Name: </b>", $_SESSION["fname"] ,"<b> Last Name: </b>", $_SESSION["lname"] ;
					echo "<br>";
			}
			
			echo "<form  method='post' action='multipleUsers.php'>";
			echo "<label>Username: </label>";
			echo "<input placeholder='username' name='adduname' type='text' required>";
			echo "<br>";
			echo "<input type='submit' value='ADD'>";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			
		}
		else{ //no matches
			echo "Unfortunately, there is no one named <b>", $_SESSION["fname"]," ", $_SESSION["lname"], "</b> using this application. Perhaps you have typed in the wrong name.";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='addFriends.php'>Try Again</a>";
					echo "<br>";
					echo"<a href='manageFG.php'>Return To Manage Friend Group</a>";
		}
	}	
	/////////////////	
	else{
	echo "You are not the creator of the friend group. Only the creator may add a person to the group.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='manageOptions.php'>Back to friend group options</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Home</a>";
  }
 }
 ?>