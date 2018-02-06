<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
 
 
  $_SESSION["friendGroupSelectedforViewing"] = $_POST['fgchosenSelected'];
   $test = $_SESSION["user_name"];
   $pub = "public";
   
   
   
   if (empty($_SESSION["friendGroupSelectedforViewing"]))
   {
	echo "No friend group chosen";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='viewSelected.php'>Try Again</a>";
  }
  elseif(strcasecmp($_SESSION["friendGroupSelectedforViewing"], $pub) == 0)
  {//want to view public content	   
			$stmt = $conn->prepare("select person.username, content.id, content.username, content.timest, content.content_name, content.file_path 
									from person, content left outer join share on share.id = content.id
									where public = 1 and person.username = ? 
									order by content.timest desc");
			$stmt->bind_param('s', $test);
				$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($person, $id, $user, $timest, $content_name, $content_msg);
		
			if ($stmt->num_rows == 0) //no public content posted
			{
				echo "<h1> Displaying content from ", $_SESSION["friendGroupSelectedforViewing"], "</h1>" ;
				echo "<br>";
				echo "<html>";
				echo "<style> body{ background-color:orange;text-align:center} div{ background-color:#ff6666; text-align:center; width:30%; margin:auto; border-radius:25pt}</style>";
				echo "<h1> oh no. it seems no one has shared content in: 'public' </h1>" ;
				echo "<br>";
				echo"<a href='upload.html'>Be the first to post something here!</a>";
				echo "<br>";
				echo"<a href='viewSelected.php'>Back</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Home</a>";
			}
			elseif ($stmt->num_rows > 0) {
				  echo "<h1> Displaying content from: ", $_SESSION["friendGroupSelectedforViewing"], "</h1>" ;
				  while($stmt->fetch()) {
						echo "<br>";
						echo "<html>";
						echo "<style> body{ background-color:orange;text-align:center} div{ background-color:#ff6666; text-align:center; width:30%; margin:auto; border-radius:25pt;border-style: solid;}</style>";
						//echo "<style> div{ background-color:orange; text-align:center; border-radius:25pt}</style>";
						echo "<div> <b>Content Id:</b> ";
						echo $id;
						echo "<br>";
						echo "<b>Content Creator: </b>", $user;
						echo "<br>";
						echo "<b>Timestamp:</b> ", $timest;
						echo "<br>";
						echo "<b>Content Name </b>: ", $content_name;
						echo "<br>";
						echo "<b>Content Message </b>: ", $content_msg;
						echo "</div>";
						//echo "<br>";
						//echo " - Name: " . $row["first_name"]. " " . $row["last_name"]. "<br>";
					}
					echo "<hr/>";
					echo "<br>";
					echo"<a href='viewSelected.php'>Back</a>";
					echo "<br>";
					echo"<a href='viewInfo.php'>Home</a>";
			}
  
  
  }
   else //friends group posts
   {  
	//check if user is part of group selected
		$chkstmt = $conn->prepare("  SELECT group_name from member
									where username = ? and group_name =?");
			$chkstmt->bind_param('ss', $test, $_SESSION["friendGroupSelectedforViewing"] );
				$chkstmt->execute();
			$chkstmt->store_result();
			
			if ($chkstmt->num_rows == 0){ // not part of group entered
				echo "<html>";
				echo "<style> body{ background-color:orange;text-align:center} div{ background-color:#ff6666; text-align:center; width:30%; margin:auto; border-radius:25pt}</style>";
				echo "<h1> You are not part of a group called: ", $_SESSION["friendGroupSelectedforViewing"], "</h1>" ;
				echo "<br>";
				echo"<a href='viewSelected.php'>Back</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Home</a>";
			
			}
			else{ // part of group
	   
			$stmt = $conn->prepare("select person.username, content.id, content.username, content.timest, content.content_name, content.file_path 
									from person, content left outer join share on share.id = content.id
									where public = 0 and person.username = ? and GROUP_name = ?
									order by content.timest desc");
			$stmt->bind_param('ss', $test, $_SESSION["friendGroupSelectedforViewing"] );
				$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($person, $id, $user, $timest, $content_name, $content_msg);	
			
			if ($stmt->num_rows == 0) 
			{
				echo "<h1> Displaying content from: ", $_SESSION["friendGroupSelectedforViewing"], "</h1>" ;
				echo "<br>";
				echo "<html>";
				echo "<style> body{ background-color:orange;text-align:center} div{ background-color:#ff6666; text-align:center; width:30%; margin:auto; border-radius:25pt;border-style: solid;}</style>";
				echo "<h1> oh no. it seems no one has shared content in this group! </h1>" ;
				echo "<br>";
				echo"<a href='upload.html'>Be the first to post something here! ~~~ </a>";
				echo "<br>";
				echo"<a href='viewSelected.php'>Back</a>";
				echo "<br>";
				echo"<a href='viewInfo.php'>Home</a>";
			}
			elseif ($stmt->num_rows > 0) {
				echo "<h1> Displaying content from: ", $_SESSION["friendGroupSelectedforViewing"], "</h1>" ;
				  while($stmt->fetch()) {
						echo "<br>";
						echo "<html>";
						echo "<style> body{ background-color:orange;text-align:center} div{ background-color:#ff6666; text-align:center; width:30%; margin:auto; border-radius:25pt; border-style: solid;}</style>";
						//echo "<style> div{ background-color:orange; text-align:center; border-radius:25pt}</style>";
						echo "<div> <b>Content Id:</b> ";
						echo $id;
						echo "<br>";
						echo "<b>Content Creator: </b>", $user;
						echo "<br>";
						echo "<b>Timestamp:</b> ", $timest;
						echo "<br>";
						echo "<b>Content Name </b>: ", $content_name;
						echo "<br>";
						echo "<b>Content Message </b>: ", $content_msg;
						echo "</div>";
						//echo "<br>";
						//echo " - Name: " . $row["first_name"]. " " . $row["last_name"]. "<br>";
					}
			echo "<hr/>";
			echo "<br>";
			echo"<a href='viewSelected.php'>Back</a>";
			echo "<br>";
			echo"<a href='viewInfo.php'>Home</a>";
			}
			
		}
	}
  

?>