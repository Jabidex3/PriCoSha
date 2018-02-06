<?php
 session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
 $test = $_SESSION["user_name"];
  echo  'Logged in As: ', $test;
  echo "<br>";
  echo "<style> a{
    background-color: black;
    color: white;
    padding: 10px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
	
}

a:hover, a:active {
    background-color: white;
    color: black;
}
a+a{margin-left:15px}
</style>";
  echo "<a href='logout.php'>Logout</a>";
  echo "<a href='groupCreation.php'>Create a group</a>";
  echo "<a href='manageFG.php'>Manage Friend Groups</a>";
  echo "<a href='viewSelected.php'>View Content By Selected Group</a>";
  echo "<a href='upload.html'>Add Content</a>";
  echo "<a href='deleteContent.php'>Delete Content</a>";
  echo "<a href='tagContent.php'>Tag Content</a>";
  echo "<a href='manageTag.php'>Manage Tags</a>";
 // echo "<a href='deleteFriendgroup.php'>Delete Friendgroup</a>";
 echo "<hr/>";
 
 echo "<h1> Content: </h1>";
 $result = $conn->prepare("select person.username, content.id, content.username, content.timest, content.content_name, content.file_path 
from person, content left outer join share on share.id = content.id
where (public = 1 or (person.username, share.group_name) in (select username, group_name from member natural left outer join friendgroup) or (content.id, person.username) in (SELECT id, username from content)) and person.username = ?
order by content.timest desc");
 $result->bind_param('s', $test);
	$result->execute();
 $result->store_result();
  $result->bind_result($person, $id, $user, $timest, $content_name, $content_msg);
 if ($result->num_rows > 0) {
    // output data of each row
    while($result->fetch()) {

		echo "<br>";
		echo "<html>";
		echo "<style> body{ background-color:orange;} div{ border-style: solid;background-color:#ff6666; text-align:center; width:30%; margin:auto; border-radius:25pt}</style>";
		echo "<style> .commentBox{ padding: 10px; background-color:yellow; text-align:center;  width:50%; border-radius:0pt;border-style: solid;} textarea {resize: none;}</style>";
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
	echo "<br>";
} 

echo "<div class ='commentBox'>";

	echo "	<form action='submitComment.php' method='post'>";
		echo "	<label>Which content would you like to comment on?</label>";
		echo "	<input type='text' name='contentnumentered' placeholder='enter content id' required>";
		echo "	<br>";
		echo "	<br>";
		echo "	<label>Enter your comment:</label>";
		echo "	<br>";
		echo "<textarea name = 'insertComment'  cols = '50' rows = '5' required></textarea>";
		echo "	<br>";
		echo "	<input type='submit' value='Submit'>";
		echo "	<br>";
	echo "	</form>";
echo "</div>";

echo "<hr/>";
 echo "<h1> Tags: </h1>";
$taggees = $conn->prepare("select content.id, tag.username_taggee, tag.username_tagger
from person, content left outer join share on share.id = content.id, tag
where content.id = tag.id and (public = 1 or (person.username, share.group_name) in (select username, group_name from member natural left outer join friendgroup) or (content.id, person.username) in (SELECT id, username from content)) and person.username = ? and tag.status = 1");
 $taggees->bind_param('s', $test);
	$taggees->execute();
 $taggees->store_result();
  $taggees->bind_result($tid, $taggee, $tagger);
 if ($taggees->num_rows > 0) {
	$prev_id = 0;
	echo "<div class = 'tag'>";
	echo "<style> .tag{ background-color:#0080ff; text-align:center; width:30%; margin:auto; border-radius:25pt; padding: 5px} </style>";
		while($taggees->fetch() ) {
			if($tid != $prev_id){
				echo "</span>";
			//	echo "<br>";
				echo "<br>";
				echo "<span> <b>People Tagged for Content ID: </b> ", $tid;
				$prev_id= $tid;
			}
			echo "<br>";
			echo $taggee, ' tagged by ', $tagger;	
		}
		echo "</span>";
	echo "</div>";
} 

echo "<br>";
echo "<hr/>";
 echo "<h1> Comments: </h1>";
$comments = $conn->prepare("select content.id, comment.comment_text, comment.username
from person, content left outer join share on share.id = content.id, comment
where content.id = comment.id and (public = 1 or (person.username, share.group_name) in (select username, group_name from member natural left outer join friendgroup) or (content.id, person.username) in (SELECT id, username from content)) and person.username = ?");
 $comments->bind_param('s', $test);
	$comments->execute();
 $comments->store_result();
 $comments->bind_result($tid, $comment, $username);
 if ($comments->num_rows > 0) {
		$prev_id = 0;
		echo "<div class = 'comment'>";
		echo "<style> .comment{ background-color:#59b300; text-align:center; width:30%; margin:auto; border-radius:25pt; padding: 5px} </style>";
		while($comments->fetch() ) {
			if($tid != $prev_id){
				echo "</span>";
				echo "<br>";
				echo "<span><b> Comments for Content ID: </b> ", $tid;
				$prev_id =  $tid;
			}
			echo "<br>";
			echo $comment, " - ", $username;
			
			
		}
		echo "</span>";
		echo "</div>";
		//echo "<br>";
 }
else {
    echo "0 results";
}
?>	