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
	echo"<a href='vdeleteContent.php'>Try Again</a>";
  }
   elseif (empty($_SESSION["Tagger"])){
	echo "Message is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='.php'>Try Again</a>";
  }
  else{
		
		$stmt = $conn->prepare("Select id from tag where id = ? and username_tagger = ? and username_taggee = ?");
		$stmt->bind_param('sss', $_SESSION["content_ID"], $_SESSION["Tagger"], $test);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($checkID);
		$stmt->fetch();
		if($checkID != "")
		{	  
			$stmt = $conn->prepare("Delete from tag where id = ? and username_tagger = ? and username_taggee = ?");
			$stmt->bind_param('sss', $_SESSION["content_ID"], $_SESSION["Tagger"], $test);
			if($stmt->execute()){
				echo "Tag successfully deleted!";
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
		else
		{
			echo "Invalid ID or tagger.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='manageTag.php'>Try Again</a>";
		}
		
  }
 ?>