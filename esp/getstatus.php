<?php

include 'functions.php';

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

  

$sql = "SELECT id, hello, date FROM newthing ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

$requestedrow = mysqli_num_rows($result);
/* echo "</br>"."KJHKJHK " .$requestedrow."   </br>"; */

if ($requestedrow > 0) {
    // output data of each row

    $row = mysqli_fetch_assoc($result);
    $ADCval = $row["hello"];
    $ADCnum = intval($ADCval);
    
    
    	    echo $ADCnum;
$threshold = getFromTable(prefs, thresh);

    if($ADCnum > $threshold){
	    echo "</br>Door is currently locked!"."</br>";
	    echo "Last lock was at: ".$row["date"];
    }
    else{
	    echo "</br>Door is currently unlocked"."</br>";
	    echo "Last lock was at: ".$row["date"];
    }
/*     echo $ADCval."</br>"; */
/*     echo "id: " . $row["id"]. " - Name: " . $row["hello"]. " " . $row["date"]. "<br>"; */
    
} else {
    echo "0 results";
}

  



mysqli_close($conn);
/*

 $ip = getFromTable(prefs,ip); 
   echo $ip;
*/

?>