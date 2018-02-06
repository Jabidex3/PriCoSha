<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $_SESSION["tag_Content"] = $_POST["tagContent"];
 $_SESSION["taggee_Name"] = $_POST["taggeeName"];
 $test = $_SESSION["user_name"];
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
 
 if (empty($_SESSION["tag_Content"]))
 {
	echo "Content ID is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='tagContent.php'>Try Again</a>";
 }
 elseif (empty($_SESSION["taggee_Name"]))
 {
	echo "Name is Empty";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='tagContent.php'>Try Again</a>";
 }
 else
{
	$stmtTag = $conn->prepare("Select id from tag where id = ? and username_tagger = ? and username_taggee = ?");
	$stmtTag->bind_param('sss', $_SESSION["tag_Content"], $test,  $_SESSION["taggee_Name"]);
	$stmtTag->execute();
	$stmtTag->store_result();
	$stmtTag->bind_result($alreadyExist);
	$stmtTag->fetch();
	
		if($alreadyExist == "")
		{
			$stmt = $conn->prepare("Select id, public from content where id = ?"); //CHECK IF CONTENT IS PUBLIC
			$stmt->bind_param('s', $_SESSION["tag_Content"]);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($contentExist, $isPublic);
			$stmt->fetch();
			
			$stmt2 = $conn->prepare("Select username from person where username = ?");
			$stmt2->bind_param('s',  $_SESSION["taggee_Name"]);
			$stmt2->execute();
			$stmt2->store_result();
			$stmt2->bind_result($tagMember);
			$stmt2->fetch();
	
			if ($contentExist != "" and $tagMember != "")
			{
				$stmt3 = $conn->prepare("Select group_name, username from share where id = ?");
				$stmt3->bind_param('s', $_SESSION["tag_Content"]);
				$stmt3->execute();
				$stmt3->store_result();
				$stmt3->bind_result($friendGroup, $groupCreator);
				$stmt3->fetch();
				
				$stmt4 = $conn->prepare("Select username from member where username = ? and group_name = ? and username_creator = ?");
				$stmt4->bind_param('sss', $test, $friendGroup, $groupCreator);
				$stmt4->execute();
				$stmt4->store_result();
				$stmt4->bind_result($isMember);
				$stmt4->fetch();
				
				if ($isPublic == 1)
				{
						if($test == $tagMember)
						{
							$stmtInsSelf = $conn->prepare("Insert into tag values(?, ?, ?, CURRENT_TIMESTAMP, 1)");
							$stmtInsSelf->bind_param('sss', $_SESSION["tag_Content"], $test, $tagMember);
							if($stmtInsSelf->execute())
							{
								echo "Successfully tagged!";
								echo "<html>";
								echo "<style> body{ background-color:orange; text-align:center}	</style>";
								echo "<br>";
								echo"<a href='tagContent.php'>Tag Another Content</a>";
								echo "<br>";
								echo"<a href='viewInfo.php'>Back to main page</a>";
							}
						}
						
						$stmtIns = $conn->prepare("Insert into tag values(?, ?, ?, CURRENT_TIMESTAMP, 0)");
						$stmtIns->bind_param('sss', $_SESSION["tag_Content"], $test, $_SESSION["taggee_Name"]);
						if($stmtIns->execute())
						{
							echo "Successfully tagged!";
							echo "<html>";
							echo "<style> body{ background-color:orange; text-align:center}	</style>";
							echo "<br>";
							echo"<a href='tagContent.php'>Tag Another Content</a>";
							echo "<br>";
							echo"<a href='viewInfo.php'>Back to main page</a>";
						}
				}
				
				else 
				{
					if($test == $tagMember and $isMember != "")
					{
						$stmtInsSelf = $conn->prepare("Insert into tag values(?, ?, ?, CURRENT_TIMESTAMP, 1)");
						$stmtInsSelf->bind_param('sss', $_SESSION["tag_Content"], $test, $tagMember);
						if($stmtInsSelf->execute())
						{
							echo "Successfully tagged!";
							echo "<html>";
							echo "<style> body{ background-color:orange; text-align:center}	</style>";
							echo "<br>";
							echo"<a href='tagContent.php'>Tag Another Content</a>";
							echo "<br>";
							echo"<a href='viewInfo.php'>Back to main page</a>";
						}
					}
					
					if($isMember != "")
					{
						$stmt5 = $conn->prepare("Select username from member where username = ? and group_name = ? and username_creator = ?");
						$stmt5->bind_param('sss', $tagMember, $friendGroup, $groupCreator);
						$stmt5->execute();
						$stmt5->store_result();
						$stmt5->bind_result($isMember2);
						$stmt5->fetch();
						
						if ($isMember2 != "")
						{
							$stmtIns2 = $conn->prepare("Insert into tag values(?, ?, ?, CURRENT_TIMESTAMP, 0)");
							$stmtIns2->bind_param('sss', $_SESSION["tag_Content"], $test, $_SESSION["taggee_Name"]);
							if($stmtIns2->execute())
							{
								echo "Successfully tagged!";
								echo "<html>";
								echo "<style> body{ background-color:orange; text-align:center}	</style>";
								echo "<br>";
								echo"<a href='tagContent.php'>Tag Another Content</a>";
								echo "<br>";
								echo"<a href='viewInfo.php'>Back to main page</a>";
/*									
								echo "friendgroup ", $friendGroup;
								echo "groupcreator ", $groupCreator;
								echo "taggee ", $tagMember;
								echo "tagger ", $test;
*/								}
						}
						else 
						{
							echo "Taggee is not in same friendgroup.";
							echo "<html>";
							echo "<style> body{ background-color:orange; text-align:center}	</style>";
							echo "<br>";
							echo"<a href='tagContent.php'>Try Again</a>";
						}
					}
					else
					{
						echo "Content is not available to user.";
						echo "<html>";
						echo "<style> body{ background-color:orange; text-align:center}	</style>";
						echo "<br>";
						echo"<a href='tagContent.php'>Try Again</a>";
					}
				}
			}
			else
			{
				echo "Taggee or id does not exist.";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='tagContent.php'>Try Again</a>";
			}
		}
		else
		{
			echo "Tag already exist.";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='tagContent.php'>Try Again</a>";
		}
}	
?>