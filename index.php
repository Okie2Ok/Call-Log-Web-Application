<head>
	<title>Call Log</title>
	<style>
		body {
			margin: 0;
			padding: 0;
			font-family: Arial, sans-serif;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			height: 100vh;
			background-color: #f5f5f5;
			background-image: url(Untitled.png);
			background-repeat: no-repeat;
			background-attachment: fixed;
            background-size: cover;
		}
		header {
			background-color: #333;
			color: #fff;
			padding: 10px;
			text-align: center;
			margin-bottom: 20px;
		}
		h1 {
			margin: 0;
			padding: 10px;
			font-size: 32px;
		}
		main {
			margin: 10px;
			width: 600px;
			background-color: #fff;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
			padding: 30px;
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}
		table {
			border-collapse: collapse;
			width: 100%;
			margin-bottom: 20px;
		}
		th, td {
			text-align: left;
			padding: 8px;
			border-bottom: 1px solid #ddd;
		}
		tr:nth-child(even) {
			background-color: #f2f2f2;
		}
		form {
			margin-top: 20px;
			width: 100%;
		}
		input[type="text"], textarea {
			padding: 8px;
			border-radius: 5px;
			border: none;
			margin-bottom: 10px;
			width: 100%;
			box-sizing: border-box;
			font-size: 16px;
		}
		input[type="submit"] {
			background-color: #4CAF50;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			font-size: 16px;
		}
		input[type="submit"]:hover {
			background-color: #45a049;
		}
	</style>
</head>
<body>
	<header>
		<h1>Call Log</h1>
	</header>
	<main>
		<?php
		
		include "database.php";
		?>
		<h2>New call record:</h2>
		<form method="post">
			<label for="phone">Phone number:</label>
			<input type="text" id="phone" name="phone" required>
			<br>
			<label for="note">Note:</label>
			<textarea id="note" name="note"></textarea>
			<br>
			<input type="submit" name="submit" value="Add call record">
		</form>
		<?php
				// Insert new call record
		if (isset($_POST['submit'])) {
			$phone = mysqli_real_escape_string($conn, $_POST['phone']);
			$note = $_POST['note'];

			// Check if phone number is a 10-digit number
			if (!preg_match('/^\d{10}$/', $phone)) {
				echo "<p>Please add 10-digit Phone number</p>";
			} else {
				// Use a prepared statement to insert the user input into the database
				$stmt = mysqli_prepare($conn, "INSERT INTO calls (phone, note) VALUES (?, ?)");
				mysqli_stmt_bind_param($stmt, "ss", $phone, $note);

				if (mysqli_stmt_execute($stmt)) {
					echo "<p>New call record created successfully</p>";
				} else {
					echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				}

				mysqli_stmt_close($stmt);
			}
		}
		?>
		<h2>Search by phone number:</h2>
		<form method="get" action="">
			<label for="search">Phone number:</label>
			<input type="text" id="search" name="search" required>
			<br>
			<input type="submit" name="submit" value="Search">
		</form>
		<?php
		// Search for call records by phone number
		if (isset($_GET['search'])) {
			$search = $_GET['search'];

			$sql = "SELECT * FROM calls WHERE phone = '$search'";
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
				echo "<h2>Call records for phone number $search:</h2>";
				echo "<table>";
				echo "<tr><th>Phone</th><th>Note</th><th>Action</th></tr>";
			
				while($row = mysqli_fetch_assoc($result)) {
					echo "<tr><td>" . $row['phone'] . "</td><td>" . $row['note'] . "</td><td><form method='post'><input type='hidden' name='id' value='" . $row['id'] . "'><input type='submit' name='delete' value='Delete'></form></td></tr>";
				}
			
				echo "</table>";
			} else {
				echo "<p>No call records found for phone number $search</p>";
			}
			
		}
		if (isset($_POST['delete'])) {
			$id = $_POST['id'];
		
			$sql = "DELETE FROM calls WHERE id = '$id'";
		
			if (mysqli_query($conn, $sql)) {
				echo "<p>Call record deleted successfully</p>";
			} else {
				echo "Error deleting call record: " . mysqli_error($conn);
			}
		}
		?>
</body>
</html>
