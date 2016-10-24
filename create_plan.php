<?php

// TODO: Implement this into /invest.php (requires Pedro's sexy HTML magic)

require_once("functions.php");
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;

if(isset($_POST["submit"])) {
	$wd_add = $_POST["withdraw_address"];
	$plan = $_POST["plan"];

	// Test if both addressed match the Bitcoin naming spec using regular expressions
	if (preg_match("^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$^", $wd_add)) {
		
		// Generate a UUID
		$uuid = (rand() == 1 ? 'A' : 'Z').generateRandomString(floor(rand(12, 16)));
		
		// Create a CoinBase wallet for the user
		$account = new Account(['name' => $uuid]);
		$address = new Address(['name' => $uuid]);
		$client->createAccount($account);
		$client->createAccountAddress($account, $address);
		
		// Fetch the BTC wallet address to send in GET back to invest page
		$deposit_address = $address->getAddress();

		// Update the database
		$stmt = $db->prepare("INSERT INTO users(uuid, withdrawal_address, deposit_address, plan) VALUES(?, ?, ?, ?)");
		$stmt->execute(array($uuid, $wd_add, $deposit_address, $plan));
		
		// Send user back to invest.php with their new deposit address
		header("Location: invest.php?depadd=" . $deposit_address);
	}
}
?>