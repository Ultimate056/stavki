
<?php
	$login = $_POST["login"];
	$password = $_POST["password"];
	$name = $_POST["name"];
	$email = $_POST['mail'];
	include("db_connect.php");


	$sql = "SELECT login,email FROM User";
	if($result = $conn->query($sql)){
	    foreach($result as $row){
	    	if($login == $row["login"] || $email == $row["email"])
	    		echo "<script>alert('Пользователь с таким логином уже существует'); window.location = 'registration.html'; </script>";
	    }
	}
	$sql = "INSERT INTO User (name, login, password, Wallet, email) 
			VALUES ('$name', '$login', '$password' , 0, '$email')";
	
	if($conn->query($sql)) echo "<script>alert('Вы успешно зарегистрировались!'); window.location = 'index.html'; </script>";
	else echo "<script>alert('Ошибка регистрации!'); window.location = 'index.html'; </script>";

	
	$conn->close();
	
?>