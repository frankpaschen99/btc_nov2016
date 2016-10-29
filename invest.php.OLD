<?php
	require_once("config.php");
	require_once("functions.php");
?>
<!DOCTYPE html>
<!-- Most of this page needs to be gutted/rewritten to support the new system -->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Bitwise Investments</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="A new, safer way to grow your bitcoin!" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	</head>
	<body>
		<?php
			
			// $_SESSION["balance"] = getBalance($client);
			// $_SESSION["stake"] = calc_stake($client);
			// $_SESSION["profit"] = calc_profit($client);
		?>
		<!-- Header -->
			<header id="header">
				<h1><a href="index.php">Bitwise Investments</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="invest.php">Dashboard</a></li>
						<li><a href="terms.php">Terms</a></li>
						<li><a href="https://bitcointalk.org/index.php?topic=1321732">Talk</a></li>
						<li><a href="index.php#three">Contact Us</a></li>
					</ul>
				</nav>
			</header>

		<!-- One -->
			<section id="one" class="wrapper style4 special">
				<div class="container">
					<header class="major">
						<h2>Investor Dashboard</h2>
						<p>Manage your investment</p>
					</header>
					<div class="row 150%">
						<div class="4u 12u$(medium)">
							<section class="box infobox">
								<h3 style="color:#474747;">Your Balance</h3>
								<p style="color:#858585;">&#3647; <?php echo $_SESSION["balance"]; ?></p>
							</section>
						</div>
						<div class="4u 12u$(medium)">
							<section class="box infobox">
								<h3 style="color:#474747;">Your Stake</h3>
								<p style="color:#858585;"><?php echo round($_SESSION["stake"], 2); ?>%</p>
							</section>
						</div>
						<div class="4u$ 12u$(medium)">
							<section class="box infobox">
								<h3 style="color:#474747;">Your Projected Profit</h3>
								<p style="color:#858585;">&#3647; <?php echo $_SESSION["profit"]; ?>/day</p>
							</section>
						</div>
					</div>
					<p>Input your UUID to view your statistics and transaction history.</p>
					<form action="input_uuid.php" method="GET">
						<input type='text' name='uuid' placeholder='Your UUID'><br/>
						<input type='submit' name='submit'>
					</form>
				</div>
			</section>
			<section id="one" class="wrapper style1 special">
				<div class="container">
					<div class="row 150%">
						<div class="4u 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color9 fa-arrow-up"></i>
								<h3>Invest</h3>
								<!-- plan creation form - MAKE IT PRETTY PEDRO PLS -->
							    <?php								
									// make sure it's a valid BTC address for the QR code shit
									// shown when submit is pressed
									if (isset($_GET["depadd"]) && preg_match("^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$^", $_GET["depadd"])) {
										echo("<img src=https://chart.googleapis.com/chart?cht=qr&chs=192x192&chl=" . $_GET["depadd"] . "/>");
										echo "<p>Deposit Address:<br/>" . $_GET["depadd"] . "</p>";
										echo "<p>Your UUID <strong>(Save This)</strong>:<br />" . $_GET["uuid"]. "</p>";
									} else if (!hasUniqueIDSet()) { // shown when submit is not pressed, and when they havent input a UUID
										echo "<form action='create_plan.php' method='POST'>
											<input type='text' name='plan' placeholder='plan id-soon to be a button/dropdown or something'>
											<input type='text' name='withdraw_address' placeholder='address you wish to withdraw to'><br/>" .
											//<center><div class='g-recaptcha' data-sitekey='6LffbhQTAAAAABC-WF-gGLNxK6dJR0jkOE_RsICk'></div></center><br/>
											"<input type='submit' name='submit'>
											</form>";
										echo "<p>By clicking submit, you automatically agree to our <a href=terms.php>terms & conditions</a>.</p>";
									} else {	// shown when the user inputs a UUID to view stats/addresses
										echo("<img src=https://chart.googleapis.com/chart?cht=qr&chs=192x192&chl=" . fetchDepositAddress(getSessionUUID(), $db) . "/>");
										echo "<p>Deposit Address:<br/>" . fetchDepositAddress(getSessionUUID(), $db) . "</p>";
									}
							    ?>
							</section>
						</div>
					</div>
				</div>
				<div class="container" style="text-align:left;">
					<h4>Transaction History</h4>
					<div class="table-wrapper">
						<table>
							<thead>
								<tr>
									<th>Date</th>
									<th>Description</th>
									<th>Status</th>
									<th>Amount</th>
									<th>Type</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (hasUniqueIDSet()) {
									$transactions = $client->getAccountTransactions(getAccount(getSessionUUID(), $client));
									foreach($transactions as $t) {
									$date = $t->getCreatedAt();
									$result = $date->format('Y-m-d H:i:s');
									
									echo '<td>' . $result . '</td>';
									echo '<td>' . $t->getDescription() . '</td>';
									echo '<td>' . $t->getStatus() . '</td>';
									echo '<td>' . $t->getAmount()->getAmount() . '</td>';
									echo '<td>' . $t->getType() . '</td>';
									echo '</tr>';
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		<!-- Footer -->
			<footer id="footer">
				<div class="container">
					<div class="row">
						<div class="8u 12u$(medium)">
							<ul class="copyright">
								<li>&copy; Bitwise Investments. All rights reserved.</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
	</body>
</html>
<!--
	                 !#########       #
               !########!          ##!
            !########!               ###
         !##########                  ####
       ######### #####     Scruffix    ######
        !###!      !####!              ######
          !           #####            ######!
                        !####!         #######
                           #####       #######
                             !####!   #######!
                                ####!########
             ##                   ##########
           ,######!          !#############
         ,#### ########################!####!
       ,####'     ##################!'    #####
     ,####'            #######              !####!
    ####'                                      #####
    ~##                                          ##~
-->
