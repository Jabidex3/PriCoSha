<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 
 $test = $_SESSION["user_name"];
 $_SESSION["content_ID"] = $_POST["contentID"];
 $_SESSION["Tagger"] = $_POST["Tagger"];
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
 
  if (empty($_SESSION["content_ID"])){
	echo "Message is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='manageTag.php'>Try Again</a>";
  }
   elseif (empty($_SESSION["Tagger"])){
	echo "Message is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='manageTag.php'>Try Again</a>";
  }
  else{
		$stmt = $conn->prepare("Select id, status from tag where id = ? and username_tagger = ? and username_taggee = ?");
		$stmt->bind_param('sss', $_SESSION["content_ID"], $_SESSION["Tagger"], $test);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($checkID, $checkStatus);
		$stmt->fetch();
		if ($checkStatus ==1)
		{
			echo "Tag is already accepted.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='manageTag.php'>Manage another Tag</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Back to main page</a>";
		}
		elseif($checkID != ""){
			$stmt2 = $conn->prepare("UPDATE tag SET status = 1 where id = ? and username_tagger = ? and username_taggee = ?");
			$stmt2->bind_param('sss', $_SESSION["content_ID"], $_SESSION["Tagger"], $test);
			if($stmt2->execute()){
				echo "Tag successfully accepted!";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='manageTag.php'>Manage another Tag</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Back to main page</a>";
			}
			else{
				echo "Unexpected Error.";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='manageTag.php'>Try Again</a>";
			}
		}
		else{
			echo "Invalid ID or tagger.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='manageTag.php'>Try Again</a>";
		}
  }
 ?>