<?php
	require_once("libraries/vendor/autoload.php");
	use Coinbase\Wallet\Resource\Account;
	use Coinbase\Wallet\Client;
	use Coinbase\Wallet\Configuration;
	use Coinbase\Wallet\Resource\Address;
	
	// Global variables
	$GLOBALS["ROI"] = 0.16; // 160% ROI for now
	$GLOBALS["will_site_succeed"] = false;
	
	// Configure the database here
	$DB_USER = "bwi";
	$DB_NAME = "bwi";
	$DB_PASS = "2725770A29064372233CB9F5636B30D5";
	$DB_HOST = "127.0.0.1";
	
	$db = new PDO("mysql:dbname=$DB_NAME;host=$DB_HOST", $DB_USER, $DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// Configure the Coinbase api and create a Client object
	$configuration = Configuration::apiKey('ESdH3lBFyXd1CpXn', 'Y2yLL4tLIuR9gjix79gkTnwaMO577peD');
	$client = Client::create($configuration);
	
	// Authy for admin panel
	$authy_api = new Authy\AuthyApi('LFE45z5BgyU4nVGBdjAnsSnVm4tzIVzy');
	
	error_reporting(E_ALL & ~E_NOTICE);
?>