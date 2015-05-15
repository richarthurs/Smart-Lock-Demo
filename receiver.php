<!--
/*
<?php
	$var1 = $_GET['xval'];
	
	/*
if($var1 == 300){
		echo "locked";
	}
*/
	
	$fileContent = "You have " .$var1. " apples.\r";
	
	$fileStatus = file_put_contents('text.txt', $fileContent,FILE_APPEND);
	
	if($fileStatus != false){
		echo "yay, it worked!";
	}
	else{
		echo "nope, it failed.";
	}
fil	
?>
*/
-->

<?php

receiverScript();

function receiverScript(){

$servername = "localhost";
$username = "thrifue5_espuser";
$password = "arduino";
$dbname = "thrifue5_esp";

// Create connection
$conn = mysql_connect($servername, $username, $password);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

/*
$created_date = date("Y-m-d H:i:s");
$sql = "INSERT INTO $lock(date)VALUES('$created_date')";
$result = mysql_query($sql);
*/
$myVar = $_GET['xval'];
$sql = "INSERT INTO `thrifue5_esp`.`newthing` (`id`, `hello`, `date`) VALUES (NULL, $myVar, TIMESTAMP(12))";

mysql_select_db($dbname);
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo 2;
mysql_close($conn);
}


?>