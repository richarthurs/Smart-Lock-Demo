<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

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

$myVar = "http://192.168.2.8:80/";
$sql = "INSERT INTO `DBNAME`.`prefs` (`ip`) VALUES ('$myVar')";

mysql_select_db($dbname);
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($conn);



?>