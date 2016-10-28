<html>
	<script src="js/Chart.bundle.min.js"></script>
	<script>
	
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
</html>
<?php
	require_once("functions.php");
	require_once("config.php");
	
	/*if (!isAdmin($db, getName()) || !isLoggedIn()) {
		header("https://bitwiseinvestments.com/");
		die();
	}
	
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
		$user = $row['user'];
		$message = $row['content'];
		$email = $row['email'];
		$date = $row['submitted'];
		
		echo '<td>' . $user . '</td>';
		echo '<td>' . $email . '</td>';
		echo '<td>' . $message . '</td>';
		echo '<td>' . $date . '</td>';
		echo '</tr><br />';	
	}*/

	// echo "<script>x($v);</script>";
?>
