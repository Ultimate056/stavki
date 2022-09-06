<?php	
	// Коэф

	function generate(){
		$array_kf = null;
		$i = 0;
		while($i < 5){
			$n1 = rand(10,30);
			$tmp = 0.6 * $n1; settype($tmp, "integer");
			$n2 = rand($tmp, $n1);
			$kf = $n1 / $n2;
			$array_kf[] = round($kf,2);
			$i++;
		}
		return $array_kf;
	}
	

?>