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
	$DB_USER = "btcsurge_invest";
	$DB_NAME = "btcsurge_invest";
	$DB_PASS = "uzEWeFaZpZ9rx3FF67RL9G5ymHHpbJXABcPv";
	$DB_HOST = "shared-32.ccihosting.com";
	
	$db = new PDO("mysql:dbname=$DB_NAME;host=$DB_HOST", $DB_USER, $DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// Configure the Coinbase api and create a Client object
	$configuration = Configuration::apiKey('2JVdZpgH9FhLqRja', 'DSyTdsVtW6DcCqUHu8USuFiGZA8PV0y2');
	$client = Client::create($configuration);
	
	// Authy for admin panel
	$authy_api = new Authy\AuthyApi('LFE45z5BgyU4nVGBdjAnsSnVm4tzIVzy');
	
	error_reporting(E_ALL & ~E_NOTICE);
?>