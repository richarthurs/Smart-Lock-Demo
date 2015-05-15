
<html>
<head>
<script>
function book_suggestion()  
{  
var book = document.getElementById("book").value;  
var xhr;  
 if (window.XMLHttpRequest) { // Mozilla, Safari, ...  
    xhr = new XMLHttpRequest();  
} else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
}  
var data = "book_name=" + book;  
     xhr.open("POST", "book-z.php", true);   
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                    
     xhr.send(data);  
}
</script>  
</head>
<body>
<h1>Preferences</h1>
<form action="prefs.php" method="post">
IP Address: <input type="text" name="ipval" /> <p>format: http://192.168.2.9:80/</p><br><br>
Threshold: <input type="text" name="threshval" /><br><br>
<input type="submit" />

<br/>
<br/>


</body>
</html>
