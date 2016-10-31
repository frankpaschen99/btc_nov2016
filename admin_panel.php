<?php
require_once("functions.php");
require_once("config.php");
	
$verification;
$logged_in = false;
if (isset($_POST["submit"])) {
	$token = $_POST["token"];
	$name = $_POST["name"];

	if ($name == "Frank") {
		$verification = $authy_api->verifyToken('1420166', $token);
	} else if ($name == "Pedro") {
		$verification = $authy_api->verifyToken('27878460', $token);
	} else {
		echo "Something bad happened<br />";
	}
}

if (isset($_POST["submit"]) && $verification->ok()) {
	$logged_in = true;
	echo "<h2>Frank's Super Neat and Functional Admin Panel</h2>";
	// Support tickets
	$stmt = $db->query("SELECT * FROM tickets");

	echo "<table border=1 style=width:100% height:100%>
		 <tr>
		 <td><b>User</b></td>
		 <td><b>Email</b></td>
		 <td><b>Message</b></td>
		 <td><b>Date</b></td>
		 </tr><br /><tr/>";

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$user = $row['uuid'];
		$message = $row['content'];
		$email = $row['email'];
		$date = $row['submitted'];
		
		echo '<td>' . $user . '</td>';
		echo '<td>' . $email . '</td>';
		echo '<td>' . $message . '</td>';
		echo '<td>' . $date . '</td>';
		echo '</tr><br />';	
	}
}
?>

<html>
	<script src="js/Chart.bundle.min.js"></script>
	<script>
		var ctx = document.getElementById("myChart");
		var data = {
		labels: ["January", "February", "March", "April", "May", "June", "July"],
		datasets: [
			{
				label: "My First dataset",
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1,
				data: [65, 59, 80, 81, 56, 55, 40],
			}
		]
	};
	var myBarChart = new Chart(ctx, {
		type: 'bar',
		data: data,
	});
	</script>
	<style>
	a.button {
		-webkit-appearance: button;
		-moz-appearance: button;
		appearance: button;

		text-decoration: none;
		color: initial;
	}
	</style>
	<body>
		<?php
			if (!$logged_in) {
				echo "<form method='POST' action='admin_panel.php'>
			<input type='radio' name='name' value='Frank'>Frank<br/>
			<input type='radio' name='name' value='Pedro'>Pedro<br/>
			<input type='text' name='token' placeholder='Authy Token'><br>
			<input type='submit' name='submit' value='Submit'>
		</form>";
			}
		?>
	</body>
</html>