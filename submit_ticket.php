<?php
	require_once("config.php");
	require_once("functions.php");
	
	$name = $_POST["name"];
	$email = $_POST["email"];
	$message = $_POST["message"];
	
	if (empty($email) || empty($message)) {
		header("Location: contact.php?error=required_fields_missing");
		die();
	}
	
	$stmt = $db->prepare("INSERT INTO tickets(uuid, email, content) VALUES(?, ?, ?)");
	$stmt->execute(array($name, $email, $message));
	
	header("Location: contact.php?message=success");
?>