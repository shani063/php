<?php 

set_error_handler("sqlErrorHandler");
set_time_limit(12000);

global $mysqli;
$mysqli = new mysqli("13.60.182.36","root","", "db_sailon");

echo "PING BEFORE = " . ($mysqli->ping() ? "ALIVE" : "DEAD") . "<br /><br />"; 

$sql = "SELECT SLEEP(65);";

$start = microtime(true);
$mysqli->query($sql);

echo "PING AFTER = " . ($mysqli->ping() ? "ALIVE" : "DEAD") . "<br /><br />"; 
echo "**query done**<br />";
allDone();

function allDone(){
 global $start, $sql;

 $end = microtime(true);
 echo "sql : $sql<br />";
 echo "elapsed : " . ($end - $start) . "<br />";
 echo "<br />";
}

function sqlErrorHandler($errno, $errstr, $errfile, $errline){
	 global $mysqli;
	 echo "Error : $errno<br />$errstr<br />";
	 echo "mysql_ping : " . ($mysqli->ping() ? "ALIVE" : "DEAD") . "<br />"; 
	 echo "<br />";

	 allDone();
}
?>