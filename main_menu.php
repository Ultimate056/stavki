<?php
	include("imitation_match.php");
	$connPDO = new PDO("mysql:host=localhost; dbname=ValerixBet", "root", "CDTNDJNMVT456");
	// Добавление/изменение аватарки
	if(isset($_POST['upload'])) {
		$name_img = $_FILES['ava']['name'];
		$type_img = $_FILES['ava']['type'];
		$data_img = file_get_contents($_FILES['ava']['tmp_name']);
		$stmt = $connPDO->prepare("INSERT INTO Avtr(name, s_path, s_data) VALUES(?,?,?)");
		$stmt->bindParam(1, $name_img);
		$stmt->bindParam(2, $type_img);
		$stmt->bindParam(3, $data_img);
		$stmt->execute();


		$sql = "SELECT MAX(ID) FROM Avtr";
		$result = $connPDO->query($sql);
		$row = $result->fetch();
		$idImg = $row['0'];
		$sql2 = "UPDATE User SET Avatar=$idImg WHERE id=" . $_COOKIE["id_user"];
		$res = $connPDO->query($sql2);
		setcookie("id_avatar", $idImg);
	}

	if(isset($_POST['add_money'])){
		$cmd = "UPDATE User SET Wallet=Wallet+".$_POST['add_money']." WHERE id=".$_COOKIE["id_user"];
		$result = $connPDO->query($cmd);
	}

	if(isset($_POST['delete_money'])){
		$cmd = "SELECT Wallet FROM User WHERE id=".$_COOKIE['id_user'];
		$result = $connPDO->query($cmd);
		$row = $result->fetch();
		$wal = $row[0];

		if($_POST['delete_money'] <= $wal){
			$cmd = "UPDATE User SET Wallet=Wallet-".$_POST['delete_money']." WHERE id=".$_COOKIE["id_user"];
			$result = $connPDO->query($cmd);
		}
		else{
			echo "<script>alert('Недостаточно средств для вывода!');</script>";
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ставки на спорт ValerixBet</title>
	<link rel="stylesheet" type="text/css" href="css/mainn.css"/>
</head>
<body>
	<script src="js/account.js"></script>
	<div class="header">
	</div>
	<div class="left_side">
		<div class="menu">
			<div id="account">
				<img src="images/account_icon.jpg" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a class="selected"  href="main_menu.php">Аккаунт</a></h2>
			</div>
			<div id="Admin_tools">
				<img src="images/admin.png" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a href="admin_window.php">Admin Tools</a></h2>
			</div>
			<div id="matches">
				<img src="images/match.png" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a href="matches_window.php">Матчи</a></h2>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="body_content">
			<div class="info">
				<div class="avatar" style="margin: 15px 0px 0px 15px; float: left;">
					<?php
						if(isset($_COOKIE["id_avatar"])){
							$cmd = "SELECT * FROM Avtr WHERE id=" . $_COOKIE["id_avatar"];
							$result = $connPDO->query($cmd);
							$row = $result->fetch();
							echo '<img src="data:image/jpeg;base64,'.base64_encode($row['s_data']).'" height="400px"/>';
						}
					?>
					<form name="load_avatar" method="POST" enctype="multipart/form-data">
						<input type="file" name="ava" accept=".png, .jpg, .jpeg" required/><br><br>
						<input class="b2" type="submit" name="upload" value="Загрузить аватарку" />
					</form>
					<h2>Персональные данные</h2>
					<hr>
					<?php
						$cmd = "SELECT * FROM User WHERE id=" . $_COOKIE["id_user"];
						$result = $connPDO->query($cmd);
						$row = $result->fetch();
						echo "<h3>Имя: ".$row["name"]. "</h3>";
						echo "<h3>Логин: ".$row["login"]." </h3>";
						echo "<h3>Баланс: ".$row["Wallet"]." рублей </h3>";
					?>
					<form class="money" name="add_sredtsv" method="POST">
						Пополнение кошелька <input type="number" maxlength="9" min="100" placeholder="от 100 руб." style="width: 6em;"name="add_money"/> | 
						<input type="submit" class="b1" value="Добавить денег"/>
					</form>
					<br>
					<form class="money" name="vivod_sredstv" method="POST">
						Вывод денег <input type="number" maxlength="9" min="100" placeholder="от 100 руб." style="width: 6em;" name="delete_money"/> |
						<input type="submit" class="b1" value="Вывести средства"/>
					</form>
				</div>
			</div>
			<div class="bets">
				<h2>Мои ставки</h2>
				<br>
				<table>
					<tr id="header_tr"><td>#</td><td>Ставка</td><td>Размер</td><td>Коэф</td><td>Рез-т</td><td>Выигрыш</td><td>Дата</td><td>1 Team</td><td>2 Team</td><td>Счёт</td></tr>
					<?php
						$cmd = "SELECT Bets.id, bet, summa, Bets.kf, Bets.Result, Win_size, date_match, (SELECT name FROM Team WHERE Team.id=Matches.T1),
						 (SELECT name FROM Team WHERE Team.id=Matches.T2), (SELECT Matches.Result FROM Matches WHERE Matches.id=id_match) FROM Bets LEFT OUTER JOIN Matches ON id_match=Matches.id WHERE id_user="
						.$_COOKIE["id_user"];
						$result = $connPDO->query($cmd);
						while($row = $result->fetch()){
							echo "<tr>";
							for($i = 0; $i < 10; $i++){
								echo "<td>$row[$i]</td>";
							}	
							echo "</tr>";
						}
					?>
				</table>
			</div>
		</div>
	</div>
	<div class="footer">
		<a href="https://vk.com/kopylov19" class="podpis" style="display: block; text-align: center; margin-top: 20px">Все права НЕзащищены этим человеком (с) 2022</a>
	</div>
</body>
</html>