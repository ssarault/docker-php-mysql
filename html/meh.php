<?php 
	
//	$db = new PDO("mysql:host=mysql;port=3306;dbname=phpdb", "user", "pass");
//	$result = $db->query("SELECT * FROM users;");
	$db = mysqli_connect("mysql:3306", "root", "pass", "phpdb");
	//$result = mysqli_query($db, "SELECT * FROM users");
	/*
	if ($result) {
		print "Success!";
	} else {
		print "Failed!";
	}*/

	if (mysqli_connect_errno()) {
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	} else {
		print "Success!";
	}

	$result = mysqli_query($db, "SELECT * FROM users");

	if (!$result) {
		print "No results";
	} else {
		print "got result";
		

		while ($row = mysqli_fetch_assoc($result)) {
			while (list($var, $val) = each($row)) {
				print "<B>$var</B>: $val<br/>";
			}
		}
	}

	$loaded = extension_loaded ('PDO' );

	print $loaded;
?>
