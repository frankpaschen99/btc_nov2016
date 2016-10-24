<?php

// TODO: Implement this into /invest.php (requires Pedro's sexy HTML magic)

require_once("functions.php");

if(isset($_POST["submit"])) {
	// address and plan from POST 
	$wd_add = $_POST["withdraw_address"];	// address we'll send returns to
	$plan = $_POST["plan"]; // ID of plan. dunno what the plans are til saf tells me
	
	// Test if both addressed match the Bitcoin naming spec using regular expressions
	if (preg_match("^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$^", $wd_add)) {
			
		// generated UUID
		$uuid = "temporary";
		
		// create coinbase wallet for user
		$account = new Account(['name' => $uuid]);
		$address = new Address(['name' => $uuid]);
		$client->createAccount($account);
		$client->createAccountAddress($account, $address);
		
		$deposit_address = $address->getAddress();	// tell this to the user so they can invest

		// put the information in the database
		$stmt = $db->prepare("INSERT INTO users(withdraw_address, plan, uuid) VALUES(?, ?, ?)");
		$stmt->execute(array($wd_add, $dep_add, $plan, $uuid));
		
		// hopefully everything succeeded. Send them back to the invest page
		// invest.php has to handle being given the deposit address. Relies on pedro's sexy HTML
		header("Location: invest.php?depadd=" . $deposit_address);
		
	}
}
?>