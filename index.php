<?php
require_once("config.php");
require_once("functions.php");
?>
<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>BTCSurge &mdash; Bitcoin Investing</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">

	<!-- Magnific Popup -->
	<link rel="stylesheet" href="css/magnific-popup.css">

	<!-- Owl Carousel  -->
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">

	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style.css">

	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
	</head>
	<body>

	<div class="gtco-loader"></div>

	<div id="page">
	<nav class="gtco-nav" role="navigation">
		<div class="gtco-container">
			<div class="row">
				<div class="col-xs-2">
					<div id="gtco-logo"><a href="index.html">BTCSurge</a></div>
				</div>
				<div class="col-xs-8 text-center menu-1">
					<ul>
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="#">BitcoinTalk</a></li>
						<li><a href="contact.php">Contact</a></li>
					</ul>
				</div>
				<div class="col-xs-2 text-right hidden-xs menu-2">
					<ul>
						<li class="btn-cta"><a href="#gtco-started"><span>Invest</span></a></li>
					</ul>
				</div>
			</div>

		</div>
	</nav>

	<header id="gtco-header" class="gtco-cover" role="banner" style="background-image:url(images/img_bg_1.jpg);">
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center">
					<div class="display-t">
						<div class="display-tc animate-box" data-animate-effect="fadeIn">
							<h1>BTCSurge</h1>
							<h2>The Smarter, Safer Way To Grow your Crypto</a></h2>
							<p><a href="#gtco-started" class="btn btn-default">Get Started</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<div id="gtco-features">
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<div class="feature-center animate-box" data-animate-effect="fadeIn">
						<span class="icon">
							<i class="fa fa-clock-o"></i>
						</span>
						<h3>Choose Your Plan</h3>
						<p>Don't want to leave your deposit for a long time? Select the growth plan that fits you best!</p>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="feature-center animate-box" data-animate-effect="fadeIn">
						<span class="icon">
							<i class="fa fa-line-chart"></i>
						</span>
						<h3>No Fees, Big Growth</h3>
						<p>BTCSurge will never take fees from your earnings. All the crypto generated goes straight to you!</p>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="feature-center animate-box" data-animate-effect="fadeIn">
						<span class="icon">
							<i class="fa fa-life-ring"></i>
						</span>
						<h3>get help, anytime</h3>
						<p>If you ever run into an issue, just send us an email. Our support staff would be glad to get back to you as quickly as possible!</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="gtco-started">
		<div class="gtco-container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
					<h2>Get Started</h2>
				</div>
			</div>
			<div class="row animate-box">
				<div class="col-md-12" style="text-align:center">
					<?php
						// General errors
						if (isset($_GET["error"])) {
							echo "<div class='alert alert-danger'>
								  <strong>Error!</strong> Oh no! An error occured. Message: " . $_GET["error"] . "
								</div>";
						}
						// Plan creation success
						if (isset($_GET["message"]) && $_GET["message"] == "success") {
							echo "<div class='alert alert-success'>
								  <strong>Success!</strong> Your plan was created successfully. Deposit into the wallet shown below to begin receiving returns.<br/>Be sure to save your UUID for support tickets and viewing statistics.
								</div>";
						}
						// shown when plan creation succeeded
						if (isset($_GET["depadd"]) && preg_match("^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$^", $_GET["depadd"])) {
							echo("<div class='col-md-8 col-md-offset-2 text-center gtco-heading'><img src=https://chart.googleapis.com/chart?cht=qr&chs=192x192&chl=" . $_GET["depadd"] . "/></div>");
							echo "<div class='col-md-8 col-md-offset-2 text-center gtco-heading'>
							<h4 style='color:white'>Deposit Address:<br/>" . $_GET["depadd"] . "</h4>
							</div>";
							echo "<div class='col-md-8 col-md-offset-2 text-center gtco-heading'>
							<h4 style='color:white'>Your UUID (Save This):<br />" . $_GET["uuid"]. "</h4></div>";
						} else if (!hasUniqueIDSet()) { // shown when submit is not pressed, and when they havent input a UUID
							echo "<form class='form-inline' action='create_plan.php' method='POST'>
									<div class='form-group'>
										  <select class='form-control' style='width:40%' name='plan_dropdown'>
											<option value='-1' style='color:black'>Select Plan</option>
											<option value='1' style='color:black'>24 hours</option>
											<option value='2' style='color:black'>48 hours</option>
											<option value='3' style='color:black'>5 day</option>
										  </select>
									</div>
									<div class='form-group'>
										<label for='text' class='sr-only'>Wallet Address to Withdraw to</label>
										<input type='text' style='width:40%' class='form-control' name='withdraw_address' placeholder='Wallet Address To Withdraw To'>
									</div>
									<center><div class='g-recaptcha' data-sitekey='6LffbhQTAAAAABC-WF-gGLNxK6dJR0jkOE_RsICk'></div></center>
									<br />
									<center><input type='submit' style='width:25%' name='submit' class='btn btn-default btn-block'></center>
								</form><br/><hr><br />";
							echo "<div class='col-md-8 col-md-offset-2 text-center gtco-heading'> 
									<h4 style='color:white;'>Alternatively, input your UUID to view your statistics and deposit information:</h4>
									</div>
									<form action='input_uuid.php' method='GET'>
										<center>
										<input type='text' style='width:30%' class='form-control' name='uuid' placeholder='Your UUID'><br/>
										<input type='submit' style='width:25%' class='btn btn-default btn-block' name='submit'>
										</center>
									</form></center>";
						} else {	// shown when the user inputs a UUID to view stats/addresses
							$uuid = getSessionUUID();
							echo("<div class='col-md-8 col-md-offset-2 text-center gtco-heading'><img src=https://chart.googleapis.com/chart?cht=qr&chs=192x192&chl=" . fetchDepositAddress(getSessionUUID(), $db) . "/></div>");
							echo "<div class='col-md-8 col-md-offset-2 text-center gtco-heading'>
							<h4 style='color:white'>Deposit Address:<br/>" . fetchDepositAddress($uuid, $db) . "</h4>
							</div>";
							echo "<div class='col-md-8 col-md-offset-2 text-center gtco-heading'>
							<h4 style='color:white'>Your UUID (Save This):<br />" . $uuid . "</h4></div>";
							
							// TODO: Transaction history/stats here
						}
					?>
					<br/><br />
				</div>
			</div>
		</div>

	</div>

		<div id="gtco-services">
			<div class="gtco-container">

				<div class="row animate-box">
					<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
						<h2>Growth Plans</h2>
						<p>Choose what best fits your needs for Bitcoin growth</p>
					</div>
				</div>

				<div class="row animate-box">

					<div class="gtco-tabs">
						<ul class="gtco-tab-nav">
							<li class="active"><a href="#" data-tab="1"><span class="icon visible-xs"><i class="icon-command"></i></span><span class="hidden-xs">24 Hour</span></a></li>
							<li><a href="#" data-tab="2"><span class="icon visible-xs"><i class="icon-bar-graph"></i></span><span class="hidden-xs">48 Hour</span></a></li>
							<li><a href="#" data-tab="3"><span class="icon visible-xs"><i class="icon-bag"></i></span><span class="hidden-xs">5 Day</span></a></li>
						</ul>

						<!-- Tabs -->
						<div class="gtco-tab-content-wrap">

							<div class="gtco-tab-content tab-content active" data-tab-content="1">
								<div class="col-md-6">
									<div class="icon icon-xlg">
										<i class="fa fa-fast-forward" aria-hidden="true"></i>
									</div>
								</div>
								<div class="col-md-6">
									<h2>24 Hour</h2>
									<p>This plan is best for those looking for a little padding on their bitcoin wallet.</p>

									<p>Your money is paid back on an hourly basis-- Every hour you will receive 4.29% of your deposit back in your wallet for 24hr. <hr>At the end of your growth period, you will be returned 110% of your deposit.</p>

									<div class="row">
										<div class="col-md-6">
											<h2 class="uppercase">Total Returned</h2>
											<p>103% per 24 hour cycle</p>
										</div>
										<div class="col-md-6">
											<h2 class="uppercase">Deposit Limitations</h2>
											<p>Deposits between ฿0.005 and ฿0.5<hr>Deposits outside this range will be considered donations and will not pay out.</p>
										</div>
									</div>

								</div>
							</div>

							<div class="gtco-tab-content tab-content" data-tab-content="2">
								<div class="col-md-6">
									<div class="icon icon-xlg">
										<i class="icon-bar-graph"></i>
									</div>
								</div>
								<div class="col-md-6">
									<h2>48 Hour</h2>
									<p>This plan is best for those that can afford to wait a little more time, for a lot more bitcoin.</p>

									<p>Your money is paid back every 6 hours-- Every installment you will receive 13.5% of your deposit back in your wallet for 48hr. <hr>At the end of your growth period, you will be returned 108% of your deposit.</p>

									<div class="row">
										<div class="col-md-6">
											<h2 class="uppercase">Total Returned</h2>
											<p>108% per 48 hour cycle</p>
										</div>
										<div class="col-md-6">
											<h2 class="uppercase">Deposit Limitations</h2>
											<p>Deposits between ฿0.01 and ฿.5<hr>Deposits outside this range will be considered donations and will not pay out.</p>
										</div>
									</div>

								</div>
							</div>

							<div class="gtco-tab-content tab-content" data-tab-content="3">
								<div class="col-md-6">
									<div class="icon icon-xlg">
										<i class="fa fa-btc"></i>
									</div>
								</div>
								<div class="col-md-6">
									<h2>5 Day</h2>
									<p>This plan is best for those that want a huge addition to their wallet.</p>

									<p>Your money is paid back every 24 hours-- Every installment you will receive 25% of your deposit back in your wallet. <hr>At the end of your growth period, you will be returned 125% of your deposit.</p>
									<div class="row">
										<div class="col-md-6">
											<h2 class="uppercase">Total Returned</h2>
											<p>125% per 5 day cycle</p>
										</div>
										<div class="col-md-6">
											<h2 class="uppercase">Deposit Limitations</h2>
											<p>Deposits between ฿0.01 and ฿1<hr>Deposits outside this range will be considered donations and will not pay out.</p>
										</div>
									</div>

								</div>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>

	<!-- Uncomment this when we actually have legit data to post there -->
	<!--<div id="gtco-counter" class="gtco-bg gtco-counter" style="background-image:url(images/img_bg_2.jpg);">
		<div class="gtco-container">
			<div class="row">
				<div class="display-t">
					<div class="display-tc">
						<div class="col-md-3 col-sm-6 animate-box">
							<div class="feature-center">
								<span class="icon">
									<i class="fa fa-btc"></i>
								</span>

								<span class="counter js-counter" data-from="0" data-to="22070" data-speed="5000" data-refresh-interval="50">1</span>
								<span class="counter-label">BTC Paid</span>

							</div>
						</div>
						<div class="col-md-3 col-sm-6 animate-box">
							<div class="feature-center">
								<span class="icon">
									<i class="fa fa-heart-o"></i>
								</span>

								<span class="counter js-counter" data-from="0" data-to="97" data-speed="5000" data-refresh-interval="50">1</span>
								<span class="counter-label">Users Paid</span>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 animate-box">
							<div class="feature-center">
								<span class="icon">
									<i class="fa fa-btc"></i>
								</span>
								<span class="counter js-counter" data-from="0" data-to="402" data-speed="5000" data-refresh-interval="50">1</span>
								<span class="counter-label">BTC </hr>In Progress</span>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 animate-box">
							<div class="feature-center">
								<span class="icon">
									<i class="fa fa-id-card-o"></i>
								</span>

								<span class="counter js-counter" data-from="0" data-to="212023" data-speed="5000" data-refresh-interval="50">1</span>
								<span class="counter-label">Plans </hr>In Progress</span>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>-->

	<!-- uncomment when we actually have testimonials-->
	<!--<div id="gtco-testimonial">
		<div class="gtco-container">
				<div class="row animate-box">
					<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
						<h2>Testimonial</h2>
					</div>
				</div>
				<div class="row animate-box">


					<div class="owl-carousel owl-carousel-fullwidth ">
						<div class="item">
							<div class="testimony-slide active text-center">
								<figure>
									<img src="images/person_1.jpg" alt="user">
								</figure>
								<span>Jean Doe, via <a href="#" class="twitter">Twitter</a></span>
								<blockquote>
									<p>&ldquo;Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.&rdquo;</p>
								</blockquote>
							</div>
						</div>
						<div class="item">
							<div class="testimony-slide active text-center">
								<figure>
									<img src="images/person_2.jpg" alt="user">
								</figure>
								<span>John Doe, via <a href="#" class="twitter">Twitter</a></span>
								<blockquote>
									<p>&ldquo;Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.&rdquo;</p>
								</blockquote>
							</div>
						</div>
						<div class="item">
							<div class="testimony-slide active text-center">
								<figure>
									<img src="images/person_3.jpg" alt="user">
								</figure>
								<span>John Doe, via <a href="#" class="twitter">Twitter</a></span>
								<blockquote>
									<p>&ldquo;Far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.&rdquo;</p>
								</blockquote>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>-->

	<footer id="gtco-footer" role="contentinfo">
		<div class="gtco-container">

			<div class="row copyright">
				<div class="col-md-12">
					<p class="pull-left">
						<small class="block">&copy; 2016 BTCSurge. All Rights Reserved.</small>
					</p>
				</div>
			</div>
		</div>
	</footer>

	<div class="gototop js-top">
		<a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
	</div>

	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Carousel -->
	<script src="js/owl.carousel.min.js"></script>
	<!-- countTo -->
	<script src="js/jquery.countTo.js"></script>
	<!-- Magnific Popup -->
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/magnific-popup-options.js"></script>
	<!-- Main -->
	<script src="js/main.js"></script>

	</body>
</html>
