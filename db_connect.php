
<?php
	$conn = new mysqli("localhost", "root", "CDTNDJNMVT456");
	if ($conn->connect_error) {
		 die("Connection failed: " . $conn->connect_error);
	}
	else{
		$sql = "USE valerixbet";
		$conn->query($sql);
	}
	$connPDO = new PDO("mysql:host=localhost; dbname=ValerixBet", "root", "CDTNDJNMVT456");
?>