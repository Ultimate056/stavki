<?php
	/*
		1. Выборка матчей , у которых результат NULL
		2. Сравнение дат и времени, наступило ли время матча
		3. Если наступило, то имитируем счёт игры, меняем результат вместо NULL
		4. Извлекаем все ставки всех пользователей которые ставили на этот матч
		5. Проверяем выиграл ли человек ставку по рез-там матча
		6. Если выиграл, то извлекается коэф, сумму ставки и обновляются данные таблиц
		7. Если нет, то чуть проще
	*/

?>

<?php
	include("db_connect.php");
	date_default_timezone_set('Europe/Moscow');
	$sql = "SELECT id, date_match FROM Matches WHERE Result IS NULL";
	$result = $connPDO->query($sql);
	while($i = $result->fetch()){
		$id = $i[0];
		$date = date_create_from_format("Y-m-d H:i:s", $i[1]);
		$date_now = date("Y-m-d H:i:s");
		$date_2 = date_create_from_format("Y-m-d H:i:s", $date_now);
		if($date < $date_2){
			$n1 = rand(0,10);
			$n2 = rand(0,10);
			$res = "$n1:$n2";
			$sql = "UPDATE Matches SET Result='$res' WHERE id=$id";
			$connPDO->query($sql);

			// Извлечение ставок пользователей
			$sql = "SELECT id, id_user, bet FROM Bets WHERE id_match=$id";
			$res = $connPDO->query($sql);
			while($j = $res->fetch()){
				$id_bet = $j[0];
				$id_user = $j[1];
				$res_bet = null;
				switch($j[2])
				{
					case "w1":
						if($n1 > $n2) { $res_bet="Выиграна"; }
						else { $res_bet="Проиграна"; }
						break;
					case "x1":
						if($n1 >= $n2) { $res_bet="Выиграна"; }
						else { $res_bet="Проиграна"; }
						break;
					case "x":
						if($n1 == $n2) { $res_bet="Выиграна"; }
						else { $res_bet="Проиграна"; }
						break;
					case "w2":
						if($n1 < $n2) { $res_bet="Выиграна"; }
						else { $res_bet="Проиграна"; }
						break;
					case "x2":
						if($n1 <= $n2) { $res_bet="Выиграна"; }
						else { $res_bet="Проиграна"; }
						break;
				}

				// Если ставку выиграл
				if($res_bet == "Выиграна"){
					
					// Извлекаем коэффициент
					$sql = "SELECT kf FROM Bets WHERE id=$id_bet AND id_user=$id_user";
					$res_in = $connPDO->query($sql);
					$row = $res_in->fetch();
					$kf = $row[0];

					// Извлекаем сумму ставки
					$sql = "SELECT summa FROM Bets WHERE id=$id_bet AND id_user=$id_user";
					$res_in = $connPDO->query($sql);
					$row = $res_in->fetch();
					$sum = $row[0];

					$win_size = $sum*$kf; 

					// Обновляем данные пользователя
					$sql = "UPDATE Bets SET Win_size=$win_size WHERE id=$id_bet AND id_user=$id_user";
					$res_in = $connPDO->query($sql);

					$sql = "UPDATE User SET Wallet=Wallet+$win_size WHERE id=$id_user";
					$res_in = $connPDO->query($sql);

					$sql = "UPDATE Bets SET Result='Win' WHERE id=$id_bet AND id_user=$id_user";
					$res_in = $connPDO->query($sql);
				}
				else{
					$win_size = 0;
					$sql = "UPDATE Bets SET Win_size=$win_size WHERE id=$id_bet AND id_user=$id_user";
					$res_in = $connPDO->query($sql);

					$sql = "UPDATE Bets SET Result='Lose' WHERE id=$id_bet AND id_user=$id_user";
					$res_in = $connPDO->query($sql);
				}
			}
		}
	}

?>