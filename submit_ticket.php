<?php
	require_once("config.php");
	require_once("functions.php");
	
	$name = $_POST["name"];
	$email = $_POST["email"];
	$message = $_POST["message"];
	
	$stmt = $db->prepare("INSERT INTO tickets(uuid, email, content) VALUES(?, ?, ?)");
	$stmt->execute(array($name, $email, $message));
	
	header("Location: index.php");
?>