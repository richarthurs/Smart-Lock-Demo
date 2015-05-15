<?php


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