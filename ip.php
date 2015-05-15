

<?php

// function takes the table and the value to insert
$ipval = $_GET['ipval'];

if($ipval != ''){
	StickInTable(prefs, ip, $ipval);
}

echo getFromTable(prefs,thresh);


function StickInTable($table, $column, $value){
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


?>

