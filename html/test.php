<?php
	$db = new PDO("mysql:host=mysql:3306;dbname=phpdb", "root", "pass");

	$result = $db->query("SELECT * FROM users;");

	while ($row = $result->fetch()) {
		print("{$row["username"]} {$row["password"]}");
	}
?>
