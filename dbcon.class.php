<?php
class dbcon{
	private $dbh = null;
	
	function makeConnection(){
		try {
			$this->dbh = new PDO('sqlite:ogame.db');
		}catch(PDOException $e){
			die("Database Connection Error");
			return 0;
		}
		//echo filesize("users.db");
		return 1;
	}
	
	function createTables(){
		
		try{
			$message = $this->dbh->exec("CREATE TABLE players (pid INTEGER PRIMARY KEY, name varchar(11) NOT NULL, status varchar(3) NOT NULL, alliance INTEGER)"); 
			//echo "Creation of table \"Players\" gave Message: ".$message."\n";
			$message = $this->dbh->exec("CREATE TABLE alliances (aid INTEGER PRIMARY KEY, name varchar(50) NOT NULL, tag varchar(11) NOT NULL, homepage varchar(100), logo varchar(100), open INTEGER(1))"); 
			//echo "Creation of table \"Alliances\" gave Message: ".$message."\n";
			$message = $this->dbh->exec("CREATE TABLE planets (plid INTEGER PRIMARY KEY, name varchar(50) NOT NULL, pid INTEGER, gala INTEGER, sys INTEGER, pos INTEGER)"); 
			//echo "Creation of table \"Planets\" gave Message: ".$message."\n";
			$message = $this->dbh->exec("CREATE TABLE moons (mid INTEGER PRIMARY KEY, size INTEGER)"); 
			//echo "Creation of table \"Moons\" gave Message: ".$message."\n";
			$message = $this->dbh->exec("CREATE TABLE planetsmoons (plid INTEGER PRIMARY KEY, mid INTEGER)"); 
			//echo "Creation of table \"PlanetsMoons\" gave Message: ".$message."\n";
		}
		catch(Exception $e){
			var_dump($e->getMessage());
		}
	}
	
	function insertPlayer($player){
		
		try{
			$insertPlayerQuery = $this->dbh->prepare('INSERT INTO players values(:id, :name, :status, :alliance)');
			if (empty($player['id'])) die("ID Missing");
			if (empty($player['name'])) $player['name'] = "no data";
			if (empty($player['status'])) $player['status'] = "no data";
			if (empty($player['alliance'])) $player['alliance'] = "no data";
			$insertPlayerQuery -> bindParam(':id',$player['id']);
			$insertPlayerQuery -> bindParam(':name',$player['name']);
			$insertPlayerQuery -> bindParam(':status',$player['status']);
			$insertPlayerQuery -> bindParam(':alliance',$player['alliance']);
			//echo "Importing Player: ID:".$player['id'].", Name: ".$player['name'].", Status: ".$player['status'].", Alliance: ".$player['alliance']."<br>\n";
			$insertPlayerQuery->execute();
			$insertPlayerResult = $insertPlayerQuery->fetchAll();
		}
		catch(Exception $e){
			//echo "trololol";
			var_dump($e->getMessage());
		}
		
	}
	
	function insertAlliance($alliance){
		
		try{
			
			$insertAllianceQuery = $this->dbh->prepare('INSERT INTO alliances values(:id, :name, :tag, :homepage, :logo, :open)');
			if (empty($alliance['id'])) die("ID Missing");
			if (empty($alliance['name'])) $alliance['name'] = "no data";
			if (empty($alliance['tag'])) $alliance['tag'] = "no data";
			if (empty($alliance['homepage'])) $alliance['homepage'] = "no data";
			if (empty($alliance['logo'])) $alliance['homepage'] = "no data";
			if (empty($alliance['open'])) $alliance['open'] = "no data";
			$insertAllianceQuery -> bindParam(':id',$alliance['id']);
			$insertAllianceQuery -> bindParam(':name',$alliance['name']);
			$insertAllianceQuery -> bindParam(':tag',$alliance['tag']);
			$insertAllianceQuery -> bindParam(':homepage',$alliance['homepage']);
			$insertAllianceQuery -> bindParam(':tag',$alliance['logo']);
			$insertAllianceQuery -> bindParam(':open',$alliance['open']);
			//echo "Importing Alliance: ID:".$alliance['id'].", Name: ".$alliance['name'].", Tag: ".$alliance['tag'].", Homepage: ".$alliance['homepage'].", tag: ".$alliance['homepage'].", Open: ".$alliance['open']."<br>";
			$insertAllianceQuery->execute();
			$insertPlayerResult = $insertAllianceQuery->fetchAll();
		}
		catch(Exception $e){
			//echo "trololol";
			var_dump($e->getMessage());
		}
		
	}
	
	
	function insertPlanet($planet){
		$moonTemp = "";
		if(!empty($planet['moon']['@attributes'])) $moonTemp = $planet['moon']['@attributes'];
		$planet = $planet['@attributes'];
		$planet['moon']=$moonTemp;
		
		try{
			$insertPlanetQuery = $this->dbh->prepare('INSERT INTO planets values(:id, :name, :pid, :gala, :sys, :pos)');
			if (empty($planet['id'])) die("ID Missing");
			if (empty($planet['name'])) $planet['name'] = "no data";
			if (empty($planet['player'])) die("Player ID Missing");
			$position = explode(":",$planet['coords']);
			$planet['gala']=$position[0];
			$planet['sys']=$position[1];
			$planet['pos']=$position[2];
			if (empty($planet['gala'])) die("Gala Missing");
			if (empty($planet['sys'])) die("Sys Missing");
			if (empty($planet['pos'])) die("Pos Missing");
			if (!empty($planet['moon'])){
				$this->insertMoon($planet['moon']);
				$this->insertPlanetsMoons($planet['id'],$planet['moon']['id']);
			}
			$insertPlanetQuery -> bindParam(':id',$planet['id']);
			$insertPlanetQuery -> bindParam(':name',$planet['name']);
			$insertPlanetQuery -> bindParam(':pid',$planet['player']);
			$insertPlanetQuery -> bindParam(':gala',$planet['gala']);
			$insertPlanetQuery -> bindParam(':sys',$planet['sys']);
			$insertPlanetQuery -> bindParam(':pos',$planet['pos']);
			//echo "Importing Planet: Planet ID:".$planet['id'].", Name: ".$planet['name'].", Player ID: ".$planet['player'].", Galaxie: ".$planet['gala'].", System: ".$planet['sys'].", Position: ".$planet['pos']."<br>\n";
			$insertPlanetQuery->execute();
			$insertPlanetResult = $insertPlanetQuery->fetchAll();
		}
		catch(Exception $e){
			//echo "trololol";
			var_dump($e->getMessage());
		}
		
	}
	
	function insertMoon($moon){
		
		try{
			$insertMoonQuery = $this->dbh->prepare('INSERT INTO moons values(:mid, :size)');
			if (empty($moon['id'])) die("Moon ID Missing");
			if (empty($moon['size'])) $moon['size'] = "0";
			$insertMoonQuery -> bindParam(':mid',$moon['id']);
			$insertMoonQuery -> bindParam(':size',$moon['size']);
			//echo "Importing Moon: Moon ID:".$moon['id'].", Size: ".$moon['size']."<br>\n";
			$insertMoonQuery->execute();
			$insertMoonResult = $insertMoonQuery->fetchAll();
		}
		catch(Exception $e){
			//echo "trololol";
			var_dump($e->getMessage());
		}
		
	}
	
	function insertPlanetsMoons($plid,$mid){
		
		try{
			$insertPlanetsMoonsQuery = $this->dbh->prepare('INSERT INTO planetsmoons values(:plid, :mid)');
			if (empty($plid)) die("Planet ID Missing");
			if (empty($mid)) die("Moon ID Missing");
			$insertPlanetsMoonsQuery -> bindParam(':plid',$plid);
			$insertPlanetsMoonsQuery -> bindParam(':mid',$mid);
			//echo "Importing PlanetsMoons: PLID:".$plid.", MID: ".$mid."<br>\n";
			$insertPlanetsMoonsQuery->execute();
			$insertPlanetsMoonsResult = $insertPlanetsMoonsQuery->fetchAll();
		}
		catch(Exception $e){
			//echo "trololol";
			var_dump($e->getMessage());
		}
	}
	
	function getMoonsPos(){
		try{
			$getMoonsPosQuery = $this->dbh->prepare('SELECT planets.gala, planets.sys, planets.pos FROM planets, planetsmoons WHERE planets.plid = planetsmoons.plid ORDER BY gala,sys,pos');
			$getMoonsPosQuery->execute();
			$getMoonsPosResult = $getMoonsPosQuery->fetchAll();
			return $getMoonsPosResult;
		}
		catch(Exception $e){
			//echo "trololol";
			var_dump($e->getMessage());
		}
	}
	
}




//echo filesize("/var/www/projects/sqlitetest/users.db");
/*$dbconnection = new dbcon();
$dbconnection->makeConnection();
$dbconnection->createTables();
$dbconnection->insertPlayer("Bob","123123");
*/
?>
