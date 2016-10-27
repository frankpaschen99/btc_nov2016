<?php
session_start();
require_once("config.php");

$uuid = $_GET["uuid"];

$res = $db->query("SELECT uuid FROM users WHERE uuid='$uuid'");

// exists in database
if ($res->rowCount() > 0) {
	$_SESSION["uuid"] = $uuid;
}

header("Location: invest.php");
?>