<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
  $_SESSION["cID"]= $_POST['contentnumentered'];
  $_SESSION["insertComment"]= $_POST['insertComment'];
   $test = $_SESSION["user_name"];
   
   if (empty($_SESSION["cID"]))
   {
	echo "No content id selected";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='viewInfo.php'>Try Again</a>";
  }
  elseif(empty($_SESSION["insertComment"]))
   {
	echo "No comment entered";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='viewInfo.php'>Try Again</a>";
  }
   elseif (strlen($_SESSION["insertComment"]) > 250)
   {
	echo "Comment is too long. Must be shorter than 250 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='viewInfo.php'>Try Again</a>";

  } 
   else{
	   $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
		//check if content is real 
	   $stmt = $conn->prepare("Select id from content where id = ?");
	   $stmt->bind_param('i',$_SESSION["cID"]);
		$stmt->execute();
	   $stmt->store_result();
		if ($stmt->num_rows == 1){
			//check if content is public or private
			 $stmt2 = $conn->prepare("Select public from content where id = ?");
			 $stmt2->bind_param('i',$_SESSION["cID"]);
				$stmt2->execute();
			 $stmt2->store_result();
			 $stmt2->bind_result($pubVal);
			 $stmt2->fetch();
			 if($pubVal == 0){ //private
				//check what friendgroup content is shared in
				$stmt4 = $conn->prepare("Select group_name, username from share where id = ?");
				 $stmt4->bind_param('i',$_SESSION["cID"]);
					$stmt4->execute();
				 $stmt4->store_result();
				 $stmt4->bind_result($gname,$gcreator);
				 $stmt4->fetch();
				 
				 //check if user is part of friendgroup that content is in
				 $stmt5 = $conn->prepare("Select username from member where group_name =? and username_creator =? and username =?");
				 $stmt5->bind_param('sss',$gname, $gcreator, $test);
					$stmt5->execute();
				 $stmt5->store_result();
				if ($stmt5->num_rows == 1){ //user is part of fg that content is shared with
					$stmt3 = $conn->prepare("INSERT INTO comment(id, username, timest, comment_text) VALUES(?, ?,CURRENT_TIMESTAMP, ?)");	
					$stmt3->bind_param("iss",  $_SESSION["cID"],  $test,  $_SESSION["insertComment"]);
					if ($stmt3->execute()) 
					{
					
						echo "Thank You for commenting.";
						echo "<html>";
						echo "<style> body{ background-color:orange; text-align:center}	</style>";
						echo "<br>";
						echo"<a href='viewInfo.php'>Home</a>";
					}
				}
				else{//not part of fg
						echo "You can not comment on a content which you dont have access to";
						echo "<html>";
						echo "<style> body{ background-color:orange; text-align:center}	</style>";
						echo "<br>";
						echo"<a href='viewInfo.php'>Home</a>";
				}
			 }
			 else{//public
				$stmt3 = $conn->prepare("INSERT INTO comment(id, username, timest, comment_text) VALUES(?, ?,CURRENT_TIMESTAMP, ?)");	
				$stmt3->bind_param("iss",  $_SESSION["cID"],  $test,  $_SESSION["insertComment"]);
				if ($stmt3->execute()) 
				{
				
					echo "Thank You for commenting.";
					echo "<html>";
					echo "<style> body{ background-color:orange; text-align:center}	</style>";
					echo "<br>";
					echo"<a href='viewInfo.php'>Home</a>";
				}
			 }
		}
		else{
			echo "there is no content with an id of: ",  $_SESSION["cID"];
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Try Again</a>";
		}
	}
  

?>