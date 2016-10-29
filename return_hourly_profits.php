<?php
/*
 * This script will be called once every 60 minutes by Cron/Task Scheduler
 * It will call the returnHourlyProfits(), which will calculate and process 
 * returns for all users, both hourly and daily.
*/
require_once("config.php");
require_once("functions.php");

returnHourlyProfits($client, $db);
?>