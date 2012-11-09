Ogame API Scripts
==============

Different Scripts that use the official Ogame API
 - createDatabase.php	| Puts the data from the XML files into one Database for easier and faster handling
 - diffBetweenMoons.php | Shows all differences between two moons, ordered by the highest distance


Prerequisites
==============

 - PHP with SQLite


API Information
==============

The Ogame API returns XML Files wich contain different information. 
They can easily be downloaded with a webbrowser. These URLs are for the german version of ogame.
For other countries you have to use a different TLD.

http://uni<youruniversenumber>.ogame.de/api/players.xml - Players - Updateinterval 1 day
http://uni<youruniversenumber>.ogame.de/api/universe.xml - Planets/Moons - Updateinterval 1 week

http://uni<youruniversenumber>.ogame.de/api/highscore.xml?category=1&type=1 - Highscores - Updateinterval 1 hour ( all possible parameter http://uni<youruniversenumber>.ogame.org/api/highscore.xml )
http://uni<youruniversenumber>.ogame.de/api/alliances.xml - Alliances - Updateinterval 1 day

http://uni<youruniversenumber>.ogame.de/api/serverData.xml - ServerInfos - Updateinterval 1 day
http://uni<youruniversenumber>.ogame.de/api/playerData.xml?id=<playerID> - PlayerInfos - Updateinterval 1 week


Additional Information
=============

 - The XML Files have to be downloaded manually (till now).


