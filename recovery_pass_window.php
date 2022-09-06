<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	require 'PHPMailer-master/src/Exception.php';
	require 'PHPMailer-master/src/PHPMailer.php';
	require 'PHPMailer-master/src/SMTP.php';
?>





<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ставки на спорт ValerixBet</title>
	<link rel="stylesheet" type="text/css" href="css/index2.css"/>
</head>
<body>

	<div class="header">
		<img id="i_men" src="images/lucky_men.jpg" align="right" >
	</div>
	<div class="content">
			<div class="form_reg">
				<h1>Восстановление пароля</h1>
				<hr>
				<form name="rec_form" method="POST">
		                <legend>Введите почту:</legend>
		                <label for="mail">Почта:</label><br>
		                <input type="email" name="mail" id="mail" minlength="3" maxlength="30" required /><br>

		                <br>
		                <input type="submit" name="but" value="Отправить код">
		                <br>
				</form>
	                <?php
	                	include("db_connect.php");
	                	if(isset($_POST['mail'])){
	                		$m = $_POST['mail'];
	                		$sql = "SELECT COUNT(email), id FROM User WHERE email='$m'";
	                		$result = $connPDO->query($sql);
	                		$row = $result->fetch();
	                		$count = $row[0];
	                		if($count >= 1){
	                				setcookie("user", $row[1]);
	                				$rand_code = rand(1000,9999);
			                		$mes = "Код для восстановления пароля: $rand_code";
			                		$sub = "Ставки на спорт. ValerixBet";
			                		setcookie("m", $m);
			                		setcookie("rc", $rand_code);
			                		// Подключаем библиотеку PHPMailer		 
									// Создаем письмо
									$mail = new PHPMailer();
									 
									// Настройки SMTP
									$mail->isSMTP();
									$mail->SMTPAuth = true;
									$mail->CharSet = 'UTF-8'; 
									$mail->Host = 'smtp.mail.ru';
									$mail->Port = 465;
									$mail->Username = 'ugrass_056@mail.ru';
									$mail->Password = 'E80qugzHAy0idJxd4ZwM';
									$mail->SMTPSecure = 'ssl';
									// От кого
									$mail->setFrom('ugrass_056@mail.ru', 'Разработчик Копылов В.Ю.');		
									 
									// Кому
									$mail->addAddress($m, 'Пользователю');
									 
									// Тема письма
									$mail->Subject = $sub;
									 
									// Тело письма
									$body = "<p><strong> $mes </strong></p>";
									$mail->msgHTML($body);
									
									$mail->send();
			                		echo '<br><form method="POST">
			                		<input type="number" name="code" required/><br>
			                		<input type="submit" name="but_rec" value="Ввести код"/>
			                		</form><br>';
	                		}
	                		else {
	                			echo "<script>alert('Пользователя с таким email не существует');</script>";
	                		}
	                	}
	                	if(isset($_POST['code'])){
	                		$c = $_POST['code'];
	                		if($c == $_COOKIE['rc']){
	                		echo '<br><form method="POST">
	                		<input type="text" name="new_pass" required/><br>
	                		<input type="submit" name="but_rec" value="Сменить пароль"/>
	                		</form><br>';
	                		}
	                		else{
	                			echo "<script>alert('Неверный код');</script>";
	                		}
	                	}
	                	if(isset($_POST['new_pass'])){
	                		$np = $_POST['new_pass'];
	                		$sql = "UPDATE User SET password='$np' WHERE id=".$_COOKIE['user'];
	                		if($connPDO->query($sql)){
	                			echo "<script>alert('Пароль успешно изменен!'); window.location = 'index.html';</script>";
	                		}
	                		else{
	                			echo "<script>alert('Непредвиденная ошибка');</script>";
	                		}
	                	}
	                ?>
                <br><br><br><br>
                <a href="index.html">вернуться на страницу входа</a>
			</div>
	</div>
	<div class="footer">
		<img id="i_bets" src="images/bets.jpg" align="left" >
	</div>
	<a href="https://vk.com/kopylov19" class="podpis">Все права НЕзащищены этим человеком (с) 2022</a>

</body>
</html>