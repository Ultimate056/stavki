<?php
	include("imitation_match.php");
	include("db_connect.php");
	if(isset($_POST['n_match']) && isset($_POST['size']) && isset($_POST['bet'])){
		// Инициализация принятых данных
		$stavka = $_POST['bet'];
		$id_m = $_POST['n_match'];
		$size_bet = $_POST['size'];
		// Извлекаем баланс(проверка)
		$sql = "SELECT Wallet FROM User WHERE id=".$_COOKIE['id_user'];
		$result = $connPDO->query($sql);
		$row = $result->fetch();
		$balance = $row[0];
		if($size_bet < $balance){
			// Извлекаем номер наряда коэффициента
			$sql = "SELECT kf FROM Matches WHERE id=$id_m";
			$result = $connPDO->query($sql);
			$row = $result->fetch();
			$number_kf = $row[0];

			// Извлекаем сам коэффициент
			$stavka = strtolower($stavka);

			$sql = "SELECT $stavka FROM koefs WHERE id=$number_kf";
			$result = $connPDO->query($sql);
			$row = $result->fetch();
			$kf = $row[0];


			// Добавляем ставку
			$sql = "INSERT INTO Bets(id_user, id_match, bet, summa, kf) VALUES(?,?,?,?,?)";
			$stmt = $connPDO->prepare($sql);
			$stmt->bindParam(1, $_COOKIE["id_user"]);
			$stmt->bindParam(2, $_POST['n_match']);
			$stmt->bindParam(3, $_POST['bet']);
			$stmt->bindParam(4, $_POST['size']);
			$stmt->bindParam(5, $kf);
			$stmt->execute();

			// Минусуем из баланса размер ставки
			$sql = "UPDATE User SET Wallet=Wallet-".$_POST['size']." WHERE id=".$_COOKIE["id_user"];
			$result = $connPDO->query($sql);
		}
		else{
			echo "<script>alert('Недостаточно средств для ставки');</script>";
		}


	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ставки на спорт ValerixBet</title>
	<link rel="stylesheet" type="text/css" href="css/matchess.css"/>
</head>
<body>
	<div class="header">
	</div>
	<div class="left_side">
		<div class="menu">
			<div id="account">
				<img src="images/account_icon.jpg" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a  href="main_menu.php">Аккаунт</a></h2>
			</div>
			<div id="Admin_tools">
				<img src="images/admin.png" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a  href="admin_window.php">Admin Tools</a></h2>
			</div>
			<div id="matchess">
				<img src="images/match.png" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a class="selected" href="matches_window.php">Матчи</a></h2>
			</div>
		</div>
		<div class="psss" align="left">
				<form method="POST">
					<span>№\матча <input type="number" style="width: 3em;" name="n_match"/></span><br>
					<span>Ставка: <select name="bet">
						<option value="w1">Победа 1</option>
						<option value="x1">Победа 1 или ничья</option>
						<option value="x">Ничья</option>
						<option value="w2">Победа 2</option>
						<option value="x2">Победа 2 или ничья</option>
					</select></span><br>
					<span>Сумма:<input type="number" placeholder="Сумма" style="width: 9em;" name="size" min="1"/></span><br><br>
					<span><input type="submit" id="post_bet" value="Сделать ставку" name="bet_button"/></span>
				</form>
		</div>
			
	</div>
	<div class="content">
		<div class="body_content">
			<div class="m_container">
			<?php
				$sql = "SELECT COUNT(id) FROM Matches WHERE Result IS NULL";
				$result = $connPDO->query($sql);
				$row = $result->fetch();
				$count = $row[0]; // Кол-во матчей
				$sql = "SELECT id, date_match, kf, (SELECT name FROM Team WHERE T1=Team.id), (SELECT name FROM Team WHERE T2=Team.id) FROM Matches WHERE Result IS NULL";
				$result = $connPDO->query($sql);
				while($row = $result->fetch()){
					echo '<div class="match"> 
					        <span style="font-size:16px;"<b>'."#$row[0] $row[3] vs $row[4]</b></span>".
					        '<span style="font-size: 14px;"><b>'."$row[1]</b></span>";
					echo '<table id="kfs"><tr><td>П1</td><td>1X</td><td>H</td><td>П2</td><td>2X</td></tr>';
					$sql = "SELECT * FROM koefs WHERE id=$row[2]";
					$res = $connPDO->query($sql);
					$j = $res->fetch();
					echo "<tr><td>$j[0]</td><td>$j[1]</td><td>$j[2]</td><td>$j[3]</td><td>$j[4]</td></tr></table></div>";
				}
			?>
			</div>
			
		</div>
	</div>
	<div class="footer">
		<a href="https://vk.com/kopylov19" class="podpis" style="display: block; text-align: center; margin-top: 20px">Все права НЕзащищены этим человеком (с) 2022</a>
	</div>
</body>
</html>