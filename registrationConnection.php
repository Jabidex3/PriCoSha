<?php
session_start();
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "pricosha";
 
 $userName = $_POST['loginUsername'];
 $password = $_POST['loginPassword'];
 $firstName = $_POST['loginFirstname'];
 $lastName = $_POST['loginLastname'];
 
 $_SESSION["user_name"] = $userName;
 $_SESSION["user_password"] = $password;
 $_SESSION["first_name"] = $firstName;
 $_SESSION["last_name"] = $lastName;
 
  if (empty($_SESSION["first_name"])){
	echo "Empty First Name";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["first_name"]) > 50){
	echo "First Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
  }
  elseif (empty($_SESSION["last_name"])){
	echo "Empty Last Name";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
  }
  elseif(strlen($_SESSION["last_name"]) > 50){
	echo "Last Name is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
  }
  elseif (empty($_SESSION["user_name"])) {
    echo "Empty Username.";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
  } 
  elseif (empty($_SESSION["user_password"])) {
    echo "Empty Password";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
	
  } 
  elseif (strlen($_SESSION["user_password"]) > 50) {
	echo "Password is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";
  } 
  elseif (strlen($_SESSION["user_name"]) > 50) {
	echo "Username is too long. Must be shorter than 50 characters";
	echo "<html>";
	echo "<style> body{ background-color:orange; text-align:center}	</style>";
	echo "<br>";
	echo"<a href='register.php'>Try Again</a>";

  } 
  else{
	   $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	   $stmt = $conn->prepare("Select username from person where username = ?");
	   $stmt->bind_param('s', $_SESSION["user_name"]);
		$stmt->execute();
	   $stmt->store_result();
		if ($stmt->num_rows == 1){
			echo "Username already taken";
			echo "<html>";
			echo "<style> body{ background-color:orange; text-align:center}	</style>";
			echo "<br>";
			echo"<a href='register.php'>Try Again</a>";
		}
		else{
			$stmt1 = $conn->prepare("INSERT INTO person (username, password, first_name, last_name) VALUES(?, md5(?), ?, ?)");	
			$stmt1->bind_param("ssss", $_SESSION["user_name"], $_SESSION["user_password"], $_SESSION["first_name"], $_SESSION["last_name"]);
			if ($stmt1->execute()) {
				echo "Congratulations! You have successfully registered to PriCoSha.";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='index.php'>Please Log In Here</a>";
			}
			else{
				echo "Unexpected Error";
				echo "<html>";
				echo "<style> body{ background-color:orange; text-align:center}	</style>";
				echo "<br>";
				echo"<a href='register.php'>Try Again</a>";
			}
		}
	}
?>	