<html>
<head>
</head>
<body>
<?php

include("dbcon.class.php");
$dbc = new dbcon();
$dbc->makeConnection();
$result = $dbc->getMoonsPos();
$prevMoonGala = "1";
$prevMoonSys = "1";
$prevMoonPos = "1";
$maxDiff= "0";
$firstMoon = "";
$secondMoon = "";
$alldiffs = "";
foreach($result as $moon){
	$galadiv = $moon['gala']-$prevMoonGala; //Galaxiedifferenz zum letzten Mond
	$sysdiv = $moon['sys']-$prevMoonSys;	//Systemdifferenz zum letzten Mond
	
	if($galadiv > 1){						//F�r jede Galaxie erh�ht sich die Differenz um 499 Systeme
		$galadiv = 499 * $galadiv;
	}

	if($sysdiv < 0){						//Wenn die Differenz der Systeme negativ ist muss ein Galaxiewechsel erfolgt sein
		$sysdiv = 499 + $sysdiv;			//-> Differenz der Systeme
	}			
	
	
	$diff = $galadiv+$sysdiv;				//Gesamtdifferenz


	$bestGala = $prevMoonGala;				//Startwert beste Galaxie
	$bestPos =  round ($diff/2);			//Beste Position genau bei der H�lfte der Differenz

	if($prevMoonSys + $bestPos > 499){		//Pr�fen ob eine Galaxie erh�ht werden muss
		$toMuch = round(($prevMoonSys + $bestPos)/499);	//Pr�fen wie viele Galaxien �bersprungen werden
		$bestGala = $bestGala + $toMuch;	//Galaxie um menge der gesprungene Galaxien erh�hen.
		$bestPos = $bestPos - ($toMuch*499);	//Gesprungene Galaxien von Differenz abziehen
	}else{
		$bestPos+=$prevMoonSys;				//Kein Galaxiesprung -> easy
	}
	
	
	

	$alldiffs[] = array("First Moon" => $prevMoonGala.":".$prevMoonSys.":".$prevMoonPos, "Second Moon" => $moon['gala'].":".$moon['sys'].":".$moon['pos'], "Diff" => $diff, "Galaxy" => $bestGala.":".$bestPos.":0" );
	$prevMoonGala = $moon['gala'];
	$prevMoonSys = $moon['sys'];
	$prevMoonPos = $moon['pos'];
}




function cmp_by_Diff($a, $b) {
  return $b["Diff"] - $a["Diff"];
}


usort($alldiffs, "cmp_by_Diff");
echo "<table border=1>\n<tr><th>First Moon</th><th>Second Moon</th><th>Difference</th><th>Best Galaxy</tr>\n";
		
foreach($alldiffs as $diff){
	echo "<tr><td>".$diff['First Moon']."</td><td>".$diff['Second Moon']."</td><td>".$diff['Diff']."</td><td>".$diff['Galaxy']."</td></tr>\n";
}
echo "</table>";

echo $prevMoonGala.":".$prevMoonSys.":".$prevMoonPos;

?>
</body>
