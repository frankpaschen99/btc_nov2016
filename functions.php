<?php
	/**
	 *	Update 10/22/16: Most of these functions are now deprecated.
	 *	These include most functions related to sql queries, user accounts, coinbase accounts, and
	 * 	transactions between coinbase accounts.
	**/
	require_once("config.php");

	use Coinbase\Wallet\Client;
	use Coinbase\Wallet\Configuration;
	use Coinbase\Wallet\Enum\CurrencyCode;
	use Coinbase\Wallet\Value\Money;
	use Coinbase\Wallet\Resource\Transaction;
	use Coinbase\Wallet\Resource\Account;

	session_start();
	
	/* Test if the SESSION variable username is empty.
	If so, return false (not logged in). Else, return true (logged in) */
	function isLoggedIn() {
		return !empty($_SESSION["username"]);
	}
	/* Return the email of a user from the database as a string */
	function fetchEmail( $username, $db ) {
		$row = userQuery("SELECT email FROM users WHERE username = ?", $db, $username);
		
		return $row['email'];
	}
	/* Return the date registered of a user from the database */
	function fetchRegisterDate( $username, $db ) {
		$row = userQuery("SELECT date_registered FROM users WHERE username = ?", $db, $username);
		
		return $row['date_registered'];
	}
	/* Return the ID of a user from the database */
	function fetchUserID( $username, $db ) {
		$row = userQuery("SELECT id FROM users WHERE username = ?", $db, $username);
		
		return $row['id'];
	}
	function fetchPlan( $uuid, $db ) {
		$row = uuidQuery("SELECT plan FROM users WHERE uuid = ?", $db, $uuid);
		return $row["plan"];
	}
	function fetchLastPayout($uuid, $db) {
		$row = uuidQuery("SELECT last_payout FROM users WHERE uuid = ?", $db, $uuid);
		return $row["last_payout"];
	}
	function fetchDepositAddress($uuid, $db) {
		$row = uuidQuery("SELECT deposit_address FROM users WHERE uuid = ?", $db, $uuid);
		return $row["deposit_address"];
	}
	function fetchWithdrawAddress($uuid, $db) {
		$query = uuidQuery("SELECT withdrawal_address FROM users WHERE uuid = ?", $db, $uuid);
		return $query["withdrawal_address"];
	}
	function fetchStaticBalance($uuid, $db) {
		$query = uuidQuery("SELECT static_bal FROM users WHERE uuid = ?", $db, $uuid);
		return $query["static_bal"]/100000000;	// return BTC instead of satoshi
	}
	function resetLastPayout($uuid, $db) {
		$query = "UPDATE users SET last_payout = NOW() WHERE uuid = ?";
		$db->prepare($query)->execute([$uuid]);	// new way of doing this apparantly
	}
	function fetchTimesPayed($uuid, $db) {
		$query = uuidQuery("SELECT num_payed FROM users WHERE uuid = ?", $db, $uuid);
		return $query["num_payed"];
	}
	function incrementTimesPayed($uuid, $db) {
		$query = "UPDATE users SET num_payed = ? WHERE uuid = ?";
		$db->prepare($query)->execute([fetchTimesPayed($uuid, $db)+1, $uuid]);
	}
	function setStaticBalance($uuid, $db, $btc_value) {
		$query = "UPDATE users SET static_bal = ? WHERE uuid = ?";
		$db->prepare($query)->execute([$btc_value*100000000, $uuid]);	// set to satoshi value, not BTC
	}
	/* Query the database WHERE username = ? using secure prepared statements */
	function userQuery( $query, $db, $username ) {
		$stmt = $db->prepare($query);
		$stmt->bindValue(1, $username, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		
		return $row;
	}
	function uuidQuery( $query, $db, $uuid ) {
		$stmt = $db->prepare($query);
		$stmt->bindValue(1, $uuid, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		
		return $row;
	}
	/* Return the balance of a user from the database as a decimal with (16,8) precision */
	function getBalance($client) {
		if (!hasUniqueIDSet()) {
			return "0.00000000";
		}
		$user_acct = getAccount(getSessionUUID(), $client);
		return $user_acct->getBalance()->getAmount();
	}
	function getAccount($uuid, $client) {
		foreach ($client->getAccounts() as $acct) {
			if ($acct->getName() == $uuid) return $acct;
		}
	}
	/* Return the username of the current logged in user as a string */
	/* Deprecated 10/26/16. Use getSessionUUID() instead */
	function getName() {
		if (isLoggedIn()) {
			return $_SESSION["username"];
		} else {
			return "not logged in";
		}
	}
	/* Query the database to test if a user is an admin and return true/false accordingly */
	function isAdmin($db, $username) {
		if (!isLoggedIn()) {
			return;
		}
		$query = userQuery("SELECT admin FROM users WHERE username = ?", $db, $username);
		return $query["admin"];
	}
	/* Return the deposit address of a user from the database as a string */
	function getWallet($db) {
		if (isLoggedIn()) {
			$query = userQuery("SELECT deposit_address FROM users WHERE username = ?" , $db, $_SESSION["username"]);
			return $query["deposit_address"];
		} else {
			return "not logged in";
		}
	}
	function calc_stake($client ) {
		if (!isLoggedIn()) {
			return;
		}
		$total = 0.00;
		$userBalance = getBalance($client);
		foreach($client->getAccounts() as $acct) {
			if ($acct->getName() == "BTC Wallet" || $acct->getName() == "Cold Wallet") {
				continue;
			}
			$total += $acct->getBalance()->getAmount();
		}
		return ($userBalance / $total) * 100;	
	}
	function calc_profit($client) {
		return sprintf('%.8F',(getBalance($client) + (getBalance($client) * $GLOBALS["ROI"])));
	}
	/* Processes a withdraw using the Coinbase API. If the transaction succeeds, the user is 
	credited in the database the negative amount they are withdrawing. */
	/* Do not use this ever again */
	function withdraw($amount, $address, $db, $client) {	
		$user_wallet = getWallet($db);
		$user_acct = getAccount(getName(), $client);

		$transaction = Transaction::send([
			'toBitcoinAddress' => $address,
			'amount'           => new Money($amount, CurrencyCode::BTC),
			'description'      => 'Withdrawal from bitwiseinvestments.com to ' . $address
		]);
		// $client->createAccountTransaction($user_acct, $transaction);		functionality removed
	}
	/* This function updates the balance of a user in the database. */
	function credit_user($username, $amount, $db) {
		// note: backticks for columns, single quotes for fields
		$db->query("UPDATE users SET `balance` = '$amount' WHERE `username` = '$username'");
		echo "User " . $username . " has been credited " . $amount . " BTC in the database.<br />";
	}
	/* Don't call this manually. Use transfer_wallets_to_pot(). */
	function credit_all_users($db, $client) {
		foreach($client->getAccounts() as $acct) {
			$amount = $acct->getBalance()->getAmount();
			$username = $acct->getName();
			
			$stmt = $db->prepare("UPDATE users SET balance = ? WHERE username = ?");
			$stmt->bindValue(1, $amount * $GLOBALS["ROI"], PDO::PARAM_STR);
			$stmt->bindValue(2, $username, PDO::PARAM_STR);
			$stmt->execute();
		}
	}
	function reset_credit($db) {
		$db->exec("UPDATE users SET balance = 0");
	}
	/* Do not use this ever again maybe. Might need to rewrite. */
	function transfer_wallets_to_pot($client, $db) {
		credit_all_users($db, $client);
		foreach($client->getAccounts() as $acct) {
			if ($acct->getName() == "BTC Wallet") {
				// Skip the iteration when it comes across the BTC Wallet because that's the wallet we're paying into.
				continue;
			}
			if ($acct->getName() == "Cold Wallet") {
				// We don't want to pull any money from the Cold Wallet either.
				continue;
			}
			if ($acct->getBalance()->getAmount() < 0.001 || $acct->getBalance()->getAmount() > 5.0000) {
				// Only pull money from accounts with a balance of > 0.001 and < 5.0000
				continue;
			}
			$transaction = Transaction::send([
				'toBitcoinAddress' => '',	// Address of the BTC Wallet
				'amount'           => new Money($acct->getBalance()->getAmount(), CurrencyCode::BTC),
				'description'      => 'Transfer to main pot. User credited in database.'
			]);
			$client->createAccountTransaction($acct, $transaction);
		}
	}
	function return_profits($client, $db) {
		foreach($client->getAccounts() as $acct) {
			$wallet_sql = userQuery("SELECT deposit_address FROM users WHERE username = ?", $db, $acct->getName());
			$user_wallet = $wallet_sql["deposit_address"];
			$user_credit = get_credit($db, $acct->getName());
			$return_on_investment = ($user_credit * $GLOBALS["ROI"]);
			$main_wallet = getAccount("BTC Wallet", $client);
			
			$transaction = Transaction::send([
				'toBitcoinAddress' => $user_wallet,
				'amount'           => new Money($return_on_investment, CurrencyCode::BTC),
				'description'      => '16% Return on Investment'
			]);
			$client->createAccountTransaction($main_wallet, $transaction);
		}
		reset_credit($db);
	}
	function get_credit($db, $username) {
		$query = userQuery("SELECT balance FROM users WHERE username = ?", $db, $username);
		return $query["balance"];
	}
	/* Stolen from internet. Pretty self explanatory. */
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	// tests if the UUID session variable is set
	function hasUniqueIDSet() {
		return !empty($_SESSION["uuid"]);
	}
	// Returns the uuid session value
	function getSessionUUID() {
		return $_SESSION["uuid"];
	}
	/* Extremely important function. Will be called by the script that runs every 60 minutes. Modified version of return_profits() */
	function returnHourlyProfits($client, $db) {
		foreach($client->getAccounts() as $acct) {
			$user_acct_name = $acct->getName();
			$acct_balance = $acct->getBalance()->getAmount();
			
			// Not gonna fuck with the BTC/Cold/ETH wallet, or accts with < min balance
			if (($user_acct_name == "BTC Wallet" || $user_acct_name == "Cold Wallet") || $user_acct_name == "ETH Wallet"
				 || $user_acct_name == "BTC Vault" || $user_acct_name == "USD Wallet" || $acct_balance < 0.005) {
				continue;
			}
			
			$wallet_sql = userQuery("SELECT withdrawal_address FROM users WHERE uuid = ?", $db, $user_acct_name);
			$user_wallet = $wallet_sql["withdrawal_address"];
			$times_payed = fetchTimesPayed($user_acct_name, $db);
			$plan = fetchPlan($user_acct_name, $db);
			$return = -1;
			
			/* Duration calculations */
			$datetime1 = new DateTime;
			$datetime1->setTimestamp(time());	// set datetime1 to the Unix epoch
			$datetime2 = new DateTime(fetchLastPayout($user_acct_name, $db));	// set datetime2 to the user's last_payout
			$elapsed = $datetime1->diff($datetime2)->format('%i');	// get the minutes between the two datetimes
			
			/* Calculate hourly returns based on plan */
			if ($plan == 1 && $acct_balance >= 0.005) {
				if ($times_payed == 24) {
					continue;
				}
				// 24 hour plan.
				if ($elapsed >= 58) {
					$return = fetchStaticBalance($user_acct_name, $db) * 0.046;
				}
			} else if ($plan == 2 && $acct_balance >= 0.01) {
				if ($times_payed == 8) {
					continue;
				}
				// 48 hour plan
				if ($elapsed >= 358) {
					$return = fetchStaticBalance($user_acct_name, $db) * 0.02625;
				}
			} else if ($plan == 3 && $acct_balance >= 0.01) {
				if ($times_payed == 5) {
					continue;
				}
				// 5 day plan
				if ($elapsed >= 7198) {
					$return = fetchStaticBalance($user_acct_name, $db) * 0.4;
				}
			} else {
				echo "Invalid plan or does not meet balance requirement for uuid=".$user_acct_name.", plan=".$plan."\n";
				return;	// invalid plan, or too little balance
			}
			
			/* It's their first payout, set their fixed balance in the database in Satoshis (x100000000) */
			if ($times_payed == 0 && !is_null($times_payed)) {
				if ($acct_balance > 0.5 && ($plan == 1 || $plan == 2)) {
					setStaticBalance($user_acct_name, $db, 0.5);
				} else if ($acct_balance > 1 && $plan == 3) {
					setStaticBalance($user_acct_name, $db, 1);
				} else {
					setStaticBalance($user_acct_name, $db, $acct_balance);
				}
			}
			
			/* If return was actually set, and they have the balance needed, create the transaction. */
			if ($return > 0 && $return <= $acct_balance) {
				// Update user's last_payout
				resetLastPayout($user_acct_name, $db);
				// Increase # of times paid by 1
				incrementTimesPayed($user_acct_name, $db);
				
				// Send the transaction
				$transaction = Transaction::send([
					'toBitcoinAddress' => $user_wallet,
					'amount'           => new Money(number_format($return, 8), CurrencyCode::BTC),
					'description'      => 'Return on Investment'
				]);
				$client->createAccountTransaction($acct, $transaction);
				echo "User Paid: uuid=".$user_acct_name.", amt=".$return.", #times paid=".$times_payed."\n";
			}
		}
	}
?>
