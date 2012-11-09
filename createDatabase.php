<?php

include("dbcon.class.php");
$players = simplexml_load_file("players.xml");
$alliances = simplexml_load_file("alliances.xml");
$universe = simplexml_load_file("universe.xml");

$players->registerXPathNamespace("a","http://uni114.ogame.de/api");
$alliances->registerXPathNamespace("a","http://uni114.ogame.de/api");
$universe->registerXPathNamespace("a","http://uni114.ogame.de/api");
$dbc = new dbcon();
$dbc->makeConnection();
//$dbc->createTables();

/*	$playersArray = $players->xpath("/a:players/a:player");
	/*print_r($playersArray);
	exit;
	foreach($playersArray as $player){
		$dbc->insertPlayer($player);
		}
	/*print_r($playersArray);*/
	
	/*$allianceArray = $alliances->xpath("/a:alliances/a:alliance");
	foreach($allianceArray as $alliance){
		print_r($alliance);
		//$dbc->insertAlliance($alliance);
		}*/
	$planetArray = $universe->xpath("/a:universe/a:planet");
	$planetArray = json_decode(json_encode((array)$planetArray),1);
	foreach($planetArray as $planet){
			$dbc->insertPlanet($planet);
		}
	
?>
