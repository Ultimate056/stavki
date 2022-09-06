
<?php

	$login = $_POST["login"];
	$password = $_POST["password"];

	include("db_connect.php");

	$sql = "SELECT * FROM User WHERE login='$login' AND password='$password'";
	if($result = $conn->query($sql)){
		if(mysqli_num_rows($result) > 0) {
			foreach($result as $row){
				setcookie("id_user", $row["id"]);
				setcookie("id_avatar", $row["Avatar"]);
			}
			echo "<script> window.location = 'main_menu.php'; </script>";
		}
		else echo "<script>alert('Вы не авторизованы, либо неправильный логин/пароль'); window.location = 'index.html'; </script>";	    
	}
?>