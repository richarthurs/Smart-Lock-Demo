<?php include 'functions.php';?>


<html>
	<head>
		<title>Smart Lock</title>
		<link rel="stylesheet" type="text/css" href="styles.css">

	</head>
	<body>
	<div id="header">
	</div>
	
	<a href="http://richarthurs.com/esp/prefsindex.php"><p id="prefs">Preferences</p></a>
	
	<!-- in the <button> tags below the ID attribute is the value sent to the arduino -->
	
	<button id="11" class="led"></button> <!-- button for pin 11 -->
	
		<h1 id="resultips"></h1>
	<script src="jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		var threshold;
		                

/*
    var ip = <?getFromTable(prefs,ip); ?>;
				$('#resultips').html(ip);
*/

            		
		LockStatus();	// returns the nicely formatted status of the lock
				
		$.ajax({
        	type: "GET",
            url: "getthresh.php",		
            data: '',
            success: function(thresh){
                console.log(thresh);
                threshold = parseInt(thresh);
            }
		});
		

		
		$.ajax({

                type: "GET",
                url: "ajax.php",	// the ajax script gets the newest value from the database, the JS sends command to the micro
                data: '',
                success: function(msg){
               		 console.log("FirstDB = "+msg);
                
			   		compare(msg,threshold);
			   	} // success function

          }); // Ajax Call
		
		
		$(".led").click(function(){
				/*
var p = $(this).attr('id'); // get id value (i.e. pin13, pin12, or pin11)
				// send HTTP GET request to the IP address with the parameter "pin" and value "p", then execute the function
				$.get("http://192.168.2.9:80/", {pin:p}); // execute get request
			$(this).toggleClass("Lock");
*/

		   $.ajax({

                type: "GET",
                url: "ajax.php",	// the ajax script gets the newest value from the database, the JS sends command to the micro
                data: 'ip=' + $('.led').val(),	// this formats the string to send, takes the id of the led class element
              //  error: myError(),
                success: function(msg){
                console.log(msg);	// msg is the most recent DB value
                var dbval;
                dbval = parseInt(msg) + 100;
                
                $.get("http://192.168.2.9:80/", {pin:msg}); // execute get request 
             
                var oldval = msg;
                console.log("myoldval = "+ oldval);
                var newval = oldval;
                console.log("beforenewval = "+newval);
                
                
                var foo = setInterval(checkdb, 1000);
				var counter = 0;
				function checkdb(){
				counter++;
				$(".led").removeClass("unlocked");
				$(".led").removeClass("locked");
				$(".led").addClass("waiting");
				$.ajax({
				type: "GET",
							 url: "getval.php",	// the ajax script gets the newest value from the database, the JS sends command to the micro
							 data: '',
							 success: function(msg){
								 newval = parseInt(msg);
								 console.log("loop = "+newval);
								 if(oldval > threshold+100){
								 $('#resultips').html("unlocking");

								 }
								  if(oldval < threshold+100){
								 $('#resultips').html("locking");

								 }
								 
								 } // success function

				}); // Ajax Call
				if(counter >= 12){
					console.log("Locking Error. Please reload.");
					console.log("DB value = "+oldval);

					$('#resultips').html("No  message received from lock. Please reload.");

					clearInterval(foo);
				}
					if(newval != oldval){
						clearInterval(foo);
						console.log("Sdfsdf");
						compare(newval,threshold);
					}
				}
             
			/*
	while(newval == oldval){
								// console.log("dbval"+dbval);
								 
				$.ajax({
				type: "GET",
							 url: "ajax.php",	// the ajax script gets the newest value from the database, the JS sends command to the micro
							 data: '',
							 success: function(msg){
								 newval = parseInt(msg) + 100;
								 } // success function

				}); // Ajax Call
				 console.log(newval);

                }
*/
                            /*
  console.log("newval = "+newval);

                $('#resultips').html("done!");
*/

							 

                
               /*
 if(msg > threshold+100){
					                $(".led").toggleClass("Lock");
	                
	            } // if 
                
                else if(msg < threshold+100){		// this sends the data back to the ESP
					while(oldval == dbval){
						 $('#resultips').html("locking");
						 $.ajax({
							 type: "GET",
							 url: "ajax.php",	// the ajax script gets the newest value from the database, the JS sends command to the micro
							 data: '',
							 success: function(msg){
								 console.log("forst"+msg);
								 oldval = parseInt(msg) + 100;
								 } // success function

						}); // Ajax Call
          			}

                $(".led").toggleClass("Lock");
						
				} // else
*/


				

             /*
   
                if(msg > threshold+100){
                
                
	                
	                $(".led").toggleClass("Lock");
	                setTimeout(function() {
	               		LockStatus();
	               						console.log("wuttt"+thingy());

					}, 15000);
					$('#resultips').html("unlocking");
						

                } // if 
                
                else if(msg < threshold+100){		// this sends the data back to the ESP
	                $(".led").toggleClass("Lock");
					setTimeout(function() {
						LockStatus();	
										console.log("wuttt"+thingy());

					}, 15000);
	                	$('#resultips').html("locking");
						
				} // else
*/
	
				} // success function
				
            }); // Ajax Call
            
                 
		}); // click
			
		
	});	// dom
	
	function compare(msg,threshold){
		if(msg > threshold+100){
		        $(".led").removeClass("unlocked");
		        $(".led").removeClass("waiting");
                $(".led").addClass("locked");
	                //locked
	           //     $(".led").toggleClass("Lock");
	              LockStatus();
						

                } // if 
                
                else if(msg < threshold+100){		// this sends the data back to the ESP
				$(".led").removeClass("locked");
		        $(".led").removeClass("waiting");
				$(".led").addClass("unlocked");
	              //  $(".led").toggleClass("Lock");

			 LockStatus();
			
				} // else

	}
	
	function thingy(){
		$.ajax({

                type: "GET",
                url: "getval.php",	// the ajax script gets the newest value from the database, the JS sends command to the micro
                data: '',
                success: function(msg){
               		 console.log(msg);
			   		 var myval;
                myval = parseInt(msg) + 100;			
                
                return myval;
                	} // success function

          }); // Ajax Call
          return 0;

	}
		
		function LockStatus(){
			$.ajax({
				type: "GET",
				url: "getstatus.php",		// returns nicely formatted status of the lock
				data: '',
				success: function(status){
					console.log(status);
					$('#resultips').html(status);
				}// success
			});	// ajax		
		} // lockstatus


		function myError(){
			$('#resultips').html("error");

		}
	</script>
	

</html>

<!--
<?php
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

  

$sql = "SELECT id, hello, date FROM newthing";
$result = mysqli_query($conn, $sql);

$requestedrow = mysqli_num_rows($result);
echo "</br>"."KJHKJHK " .$requestedrow."   </br>";

if ($requestedrow > 0) {
    // output data of each row

    $row = mysqli_fetch_assoc($result);
    $ADCval = $row["hello"];
    $ADCnum = intval($ADCval);
    
    if($ADCnum > 200){
	    echo "</br>Door is currently locked!"."</br>";
	    echo $ADCnum;
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
?>
-->

<!--
<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = mysql_connect($servername, $username, $password);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
$sql = 'SELECT id, hello, date FROM `thrifue5_esp`.`newthing`';

mysql_select_db('newthing');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
$i = 0;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
	$i++;
    echo "Connection ID :{$row['id']}  <br> ".
         "ADC Read : {$row['hello']} <br> ".
         "Last time opened : {$row['date']} <br> ".
         "--------------------------------<br>";
} 
echo "Fetched data successfully\n";
echo $i;
mysql_close($conn);
?>
-->