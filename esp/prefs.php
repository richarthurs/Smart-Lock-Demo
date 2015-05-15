


<?php

// function takes the table and the value to insert
$ipval = $_POST[ipval];
$threshval = $_POST[threshval];

if($ipval != ''){
	StickInTable(prefs, ip, $ipval);
}

if($threshval > 0){
	StickInTable(prefs, thresh, $threshval);
/* 	string http_post_data ( string "http://192.168.2.9:80/" , string $threshval); */

}

echo getFromTable(prefs,thresh);


function StickInTable($table, $column, $value){
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

$sql = "UPDATE `$dbname`.`$table` SET $column = '$value'";


mysql_select_db($dbname);
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "<p id=\"sqlprint\">Entered $value in $column\n</p><br/>";
mysql_close($conn);
}

function getFromTable($table, $column){
	$servername = "";
	$username = "";
	$password = "";
	$dbname = "";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "SELECT $column FROM `$dbname`.`$table`";
	$result = mysqli_query($conn, $sql);

	$row = mysqli_fetch_assoc($result);

	return $row[$column];
	mysql_close($conn);

}

?>

