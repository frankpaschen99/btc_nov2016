<?php
	require_once("libraries/vendor/autoload.php");
	use Coinbase\Wallet\Resource\Account;
	use Coinbase\Wallet\Client;
	use Coinbase\Wallet\Configuration;
	use Coinbase\Wallet\Resource\Address;
	
	$GLOBALS["ROI"] = 0.16; // 160% ROI for now
	
	// Configure the database here
	$DB_USER = "bwi";
	$DB_NAME = "bwi";
	$DB_PASS = "2725770A29064372233CB9F5636B30D5";
	$DB_HOST = "127.0.0.1";
	
	$db = new PDO("mysql:dbname=$DB_NAME;host=$DB_HOST", $DB_USER, $DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// Configure the Coinbase api and create a Client object
	$configuration = Configuration::apiKey('vh3GZE5QALqHONWf', 'Kcu3F2LWx6PcfKmluVNhYJvd2UHq9csY');
	$client = Client::create($configuration);
?>