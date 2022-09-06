<?php
	include("imitation_match.php");
	include("db_connect.php");
	if(isset($_POST['name_team']) && isset($_POST['type_sport']) && isset($_POST['country_team'])){
		$sql = "INSERT INTO Team(name,type_sport,country) VALUES(?,?,?)";
		$stmt = $connPDO->prepare($sql);
		$stmt->bindParam(1, $_POST['name_team']);
		$stmt->bindParam(2, $_POST['type_sport']);
		$stmt->bindParam(3, $_POST['country_team']);
		$stmt->execute();
	}
	if(isset($_POST['id_T1']) && isset($_POST['id_T2']) && isset($_POST['date'])){
		// Генерируем коэффициенты
		include("generator_kf.php");
		$arr = generate();

		// Добавляем в БД коэфы
		$sql = "INSERT INTO koefs(w1,x1,x,w2,x2) VALUES(?,?,?,?,?)";
		$j = 0;
		$stmt = $connPDO->prepare($sql);
		while($j < 5){
			$stmt->bindParam($j+1, $arr[$j]);
			$j++;
		}
		$stmt->execute();

		// Извлекаем номер последнего набора коэфиц.
		$sql = "SELECT MAX(id) FROM koefs";
		$res = $connPDO->query($sql);
		$row = $res->fetch();
		$n_koef = $row[0];

		$sql = "INSERT INTO Matches(date_match, T1, T2, kf) VALUES(?,?,?,?)";
		$stmt = $connPDO->prepare($sql);
		$stmt->bindParam(1, $_POST['date']);
		$stmt->bindParam(2, $_POST['id_T1']);
		$stmt->bindParam(3, $_POST['id_T2']);
		$stmt->bindParam(4, $n_koef);
		$stmt->execute();
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ставки на спорт ValerixBet</title>
	<link rel="stylesheet" type="text/css" href="css/adminn.css"/>
</head>
<body>
	<div class="header">
	</div>
	<div class="left_side">
		<div class="menu">
			<div id="account">
				<img src="images/account_icon.jpg" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a href="main_menu.php">Аккаунт</a></h2>
			</div>
			<div id="Admin_tools">
				<img src="images/admin.png" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a class="selected" href="admin_window.php">Admin Tools</a></h2>
			</div>
			<div id="matches">
				<img src="images/match.png" style="float:left; margin-right: 15px;">
				<h2 style="padding-top: 3px;"><a href="matches_window.php">Матчи</a></h2>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="body_content">
			<div class="teams">
				<h2>Список команд</h2>
				<table>
					<?php
						$cmd = "SELECT * FROM Team";
						$result = $connPDO->query($cmd);
						echo '<tr id="t1_header"><td>id</td><td>Название</td><td>Спорт</td><td>Страна</td></tr>';
						while($row = $result->fetch()){
							echo "<tr>";
							for($i = 0; $i < 4; $i++){
								echo "<td>$row[$i]</td>";
							}	
							echo "</tr>";
						}
					?>
				</table>
				<form name="add_team_form" method="POST">	
					<input type="text" name="name_team"  placeholder="Name Team"/><br><br>
					<input type="text" name="type_sport"  placeholder="Type Sport"/><br><br>
					<input type="text" name="country_team" placeholder="Country Team" /><br><br>
					<input type="submit" name="button_input_team" value="Add Team"/>
				</form>
			</div>
			<div class="matches">
				<h2>Добавление матча</h2><br>
				<form name="input_match" method="POST">
					<span>Номер 1-й команды(Хозяева)</span> : <input type="number" name="id_T1"/><br><br>
					<span>Номер 2-й команды(Гости)</span> : <input type="number" name="id_T2"/><br><br>
					<span>Дата и время матча</span> : <input type="datetime-local" name="date"/><br><br>
					<input type="submit" name="button_match" value="Добавить матч"/><br><br>
				</form>

			</div>
		</div>

	</div>
	<div class="footer">
		<a href="https://vk.com/kopylov19" class="podpis" style="display: block; text-align: center; margin-top: 20px">Все права НЕзащищены этим человеком (с) 2022</a>
	</div>
</body>
</html>