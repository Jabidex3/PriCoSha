<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $_SESSION["delete_Content"] = $_POST["deleteContent"];
 $test = $_SESSION["user_name"];
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
 
 if (empty($_SESSION["delete_Content"])){
	echo "Message is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='vdeleteContent.php'>Try Again</a>";
  }
  else{
	$stmt = $conn->prepare("Select username, public from content where id = ?");
	$stmt->bind_param('s', $_SESSION["delete_Content"]);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($contentCreator, $isPublic);
	$stmt->fetch();
	
	$stmt2 = $conn->prepare("Select username from share where id = ?");
	$stmt2->bind_param('s', $_SESSION["delete_Content"]);
	$stmt2->execute();
	$stmt2->store_result();
	$stmt2->bind_result($groupCreator);
	$stmt2->fetch();
	
	$stmt3 = $conn->prepare("Select username_tagger from tag where id = ?");
	$stmt3->bind_param('s', $_SESSION["delete_Content"]);
	$stmt3->execute();
	$stmt3->store_result();
	$stmt3->bind_result($usernameTag);
	$stmt3->fetch();
	
	$stmt4 = $conn->prepare("Select username from comment where id = ?");
	$stmt4->bind_param('s', $_SESSION["delete_Content"]);
	$stmt4->execute();
	$stmt4->store_result();
	$stmt4->bind_result($usernameComment);
	$stmt4->fetch();
/*	
	echo "contentCreator = ", $contentCreator;
	echo "<br/>";
	echo "isPublic = ", $isPublic;
	echo "<br/>";
	echo "groupCreator = ", $groupCreator;
	echo "<br/>";
	echo "Tagger = ", $usernameTag;
	echo "<br/>";
	echo "Comment = ", $usernameComment;
*/
//	public == 1
	if($contentCreator == "")
	{
		echo "<html>";
		echo "<style> body{ background-color:orange; text-align:center}	</style>";
		echo "<br/>";
		echo "Content does not exist";
		echo "<br/>";
		echo"<a href='deleteContent.php'>Delete another content</a>";
		echo "<br>";
		echo"<a href='viewInfo.php'>Back to main page</a>";
	}
	elseif($isPublic == 1){
		if($contentCreator == $test){
			if($groupCreator != ""){
				$stmtDelShare = $conn->prepare("Delete from share where id = ?");
				$stmtDelShare->bind_param('s', $_SESSION["delete_Content"]);
				$stmtDelShare->execute();
			}
			
			if($usernameTag != ""){
				$stmtDelTag = $conn->prepare("Delete from tag where id = ?");
				$stmtDelTag->bind_param('s', $_SESSION["delete_Content"]);
				$stmtDelTag->execute();
			}
			
			if($usernameComment != ""){
				$stmtDelComment = $conn->prepare("Delete from comment where id = ?");
				$stmtDelComment->bind_param('s', $_SESSION["delete_Content"]);
				$stmtDelComment->execute();
			}
			
			$stmtDel1 = $conn->prepare("Delete from content where id = ?");
			$stmtDel1->bind_param('s', $_SESSION["delete_Content"]);
			if($stmtDel1->execute()){
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br/>";
				echo "Delete Successfully";
				echo "<br/>";
				echo"<a href='deleteContent.php'>Delete another content</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Back to main page</a>";
			}
			else{
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br/>";
				echo "Unexpected error";
				echo "<br/>";
				echo"<a href='deleteContent.php'>Try Again</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Back to main page</a>";
			}
		}
		else{		
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br/>";
			echo "You are not the content creator.  Deletion failed.";
			echo "<br/>";
			echo"<a href='deleteContent.php'>Try Again</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Back to main page</a>";
		}
	}
	//public == 0
	else{
		if($test == $groupCreator or $test == $contentCreator){
			if($groupCreator != ""){
				$stmtDelShare1 = $conn->prepare("Delete from share where id = ?");
				$stmtDelShare1->bind_param('s', $_SESSION["delete_Content"]);
				$stmtDelShare1->execute();
			}
			
			if($usernameTag != ""){
				$stmtDelTag1 = $conn->prepare("Delete from tag where id = ?");
				$stmtDelTag1->bind_param('s', $_SESSION["delete_Content"]);
				$stmtDelTag1->execute();
			}
			
			if($usernameComment != ""){
				$stmtDelComment1 = $conn->prepare("Delete from comment where id = ?");
				$stmtDelComment1->bind_param('s', $_SESSION["delete_Content"]);
				$stmtDelComment1->execute();
			}
			
			$stmtDel3 = $conn->prepare("Delete from content where id = ?");
			$stmtDel3->bind_param('s', $_SESSION["delete_Content"]);
			if($stmtDel3->execute()){
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo "Delete Successfully";
				echo "<br/>";
				echo"<a href='deleteContent.php'>Delete another content</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Back to main page</a>";
			}
		}
		else{
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo "You must be the content creator or the group leader to delete this content. Deletion failed.";
			echo "<br/>";
			echo"<a href='deleteContent.php'>Try Again</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Back to main page</a>";
		}
	}

  }
?>