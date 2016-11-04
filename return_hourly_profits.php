<?php
/*
 * This script will be called once every 60 minutes by Cron on the Mac Mini
 * It will call the returnHourlyProfits(), which will calculate and process 
 * returns for all users, both hourly and daily.
*/
require_once("config.php");
require_once("functions.php");

function getCURLToken($db) {
	$stmt = $db->prepare("SELECT pass_hash FROM pass_data WHERE pass_key = ?");
	$stmt->bindValue(1, "curl", PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch();
	return $row["pass_hash"];
}

// Validate credentials:
$token = $_POST["token"];

if (hash_equals(getCURLToken($db), crypt($token, getCURLToken($db)))) {
	echo "Users payed out.";
	returnHourlyProfits($client, $db);
} else {
	echo "Nice try.\n";
}
?>