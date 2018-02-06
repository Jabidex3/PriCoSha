<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";


 $_SESSION["login_status"] = 0;
 $_SESSION["user_name"] = $_POST['u_name'];
 $_SESSION["user_password"] = $_POST['u_password'];

 // Create connection
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
 $stmt = $conn->prepare("Select username, password from person where username = ? and password = md5(?)");
 $stmt->bind_param('ss', $_SESSION["user_name"], $_SESSION["user_password"]);
	$stmt->execute();
 $stmt->bind_result($unme, $pwd);
 $stmt->store_result();
 if ($stmt->num_rows == 1){
	$stmt->fetch();
	//echo "Thank You";
	$_SESSION["login_status"]  = 1;
}
 else {
	echo "Invalid username and password";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='index.php'>Try Again</a>";
}

if ($_SESSION["login_status"] ==1)
{
header("Location:viewInfo.php");
}
else{
	//echo "nope";
}

?>