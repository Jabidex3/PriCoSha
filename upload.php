<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $_SESSION["shareGroup"] = 0;
 $_SESSION["insert_Content"] = $_POST["insertContent"];
 $_SESSION["insert_ContentName"] = $_POST["insertContentName"];
 $_SESSION["insert_FriendGroup"] = $_POST['insertFriendGroup'];
 $_SESSION["insert_Public"] = $_POST['insertPublic'];
 $test = $_SESSION["user_name"];

$yes = "public";
$no = "private";
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
date_default_timezone_set("America/New_York");
$todayDTE = date("Y-m-d h:i:s");
		
 if (empty($_SESSION["insert_Content"])){
	echo "Message is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='upload.php'>Try Again</a>";
  }
  elseif(empty($_SESSION["insert_Public"])){
	  echo "You have not entered a value for visibility";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Back to main page</a>";
			echo "<br/>";
			echo"<a href='upload.html'>Try Again</a>";
  }
  elseif(strlen($_SESSION["insert_ContentName"]) > 50){
	echo "content name too long. must be less than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='upload.html'>Retry</a>";
  }
  elseif(strlen($_SESSION["insert_Content"]) > 100){
	echo "content too long. must be less than 100 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='upload.html'>Retry</a>";
  }
   elseif(strlen($_SESSION["insert_FriendGroup"]) > 50){
	echo "friend group name too long. must be less than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='upload.html'>Retry</a>";
  }
  elseif(strcasecmp($_SESSION["insert_Public"], $yes) == 0)
  {
	  $stmt1 = $conn->prepare("INSERT INTO content (username, timest, file_path, content_name, public) VALUES(?, ?, ?, ?, 1)");	
		$stmt1->bind_param("ssss", $test, $todayDTE,$_SESSION["insert_Content"], $_SESSION["insert_ContentName"]);
		if ($stmt1->execute()) {
			echo "Congratulations! You have successfully created a content";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='upload.html'>Add another content</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Back to main page</a>";
		}
		else{
			echo "Unexpected error. unable to add content.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Back to main page</a>";
			echo "<br/>";
			echo"<a href='upload.html'>Try Again</a>";
		}
  }
  elseif(strcasecmp($_SESSION["insert_Public"], $no) == 0)
  {
	 if(empty($_SESSION["insert_FriendGroup"]))
	 {
		echo "no friendgroup entered";
	    echo "<html>";
		echo "<style> body{ background-color:orange; text-align:center}	</style>";
		
		echo "<br/>";
		echo"<a href='upload.html'>Try Again</a>";
		echo "<br>";
		echo"<a href='viewInfo.php'>Back to main page</a>";
	 }
	 else{
		 //check if user is part of friendgroup entered
			$stmt = $conn->prepare("Select group_name from member where username = ? and group_name = ?");
			$stmt->bind_param('ss', $test, $_SESSION["insert_FriendGroup"]);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($groupName);
			if ($stmt->num_rows == 1){
				$stmt->fetch();
				$stmt2 = $conn->prepare("INSERT INTO content (username, timest, file_path, content_name, public) VALUES(?, ?, ?, ?, 0)");	
				$stmt2->bind_param("ssss", $test, $todayDTE, $_SESSION["insert_Content"], $_SESSION["insert_ContentName"]);
				if ($stmt2->execute()) 
				{
					$stmt3 = $conn->prepare("Select id from content where username = ? and timest = ?");
					$stmt3->bind_param("ss", $test, $todayDTE);
					$stmt3->execute();
					$stmt3->store_result();
					$stmt3->bind_result($contentID);
					$stmt3->fetch();
					$stmt4 = $conn->prepare("Select username_creator from member where username = ? and group_name = ?");
					$stmt4->bind_param('ss', $test, $groupName);
					$stmt4->execute();
					$stmt4->store_result();
					$stmt4->bind_result($groupCreator);
					$stmt4->fetch();
					$stmt5 = $conn->prepare("INSERT INTO share (id, group_name,username) VALUES(?, ?, ?)");
					$stmt5->bind_param('iss', $contentID, $groupName, $groupCreator);
					$stmt5->execute();
					echo "Congratulations! You have successfully created a content";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='upload.html'>Add another content</a>";
					echo "<br>";
					echo"<a href='viewInfo.php'>Back to main page</a>";
				}
				else{
					echo "Unexpected error. unable to add content to friendgroup.";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='viewInfo.php'>Back to main page</a>";
					echo "<br/>";
					echo"<a href='upload.html'>Try Again</a>";
				}
			}
			else{
				echo "User is not part of a group named ", $_SESSION["insert_FriendGroup"];
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				
				echo "<br/>";
				echo"<a href='upload.html'>Try Again</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Back to main page</a>";
			}
	 }
  }
  else{
		echo "invalid value for visibility";
		echo "<html>";
		echo "<style> body{ background-color:orange; text-align:center}	</style>";
		
		echo "<br/>";
		echo"<a href='upload.html'>Try Again</a>";
		echo "<br>";
		echo"<a href='viewInfo.php'>Back to main page</a>";
  }
	  
 ?>
