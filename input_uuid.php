<?php
session_start();

$uuid = $_GET["uuid"];

// make sure UUID is valid/exists in database


$_SESSION["uuid"] = $uuid;
header("Location: invest.php");
?>