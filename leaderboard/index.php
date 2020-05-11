<html>
	<head>
		<link rel="Stylesheet" href="main.css" type="text/css" media="screen">
	</head>
	<body>
		<h1>ChessRun Leaderboard</h1>
		<?php
			// So here we want to display a leaderboard grabbed from the database
			// I'm thinking we dump all the categories here now, and then let CSS and JS determine what to show.
			$conn = mysqli_connect('localhost', getenv("DB_USER"), getenv("DB_PASSWORD"), 'chessrun');
			if ($conn) {
				$query = "SELECT r.username, r.runtime, r.link, r.category_id, c.category_name FROM runs r JOIN categories c ON r.category_id = c.category_id WHERE r.confirmed = 1 ORDER BY runtime";
				if ($result = $conn->query($query)) {
					// We got a result, let's build a table
					// But first we need a dropdown to select the category to display the leaderboard for that category
					// To do this, loop over our results and collate categories
					$cats = [];
					while ($row = $result->fetch_row()) { 
						$cats[$row[3]] = $row[4];
					}
					$cats = array_unique($cats);
					?>
					<h2>Select Category</h2>
					<div class="select-container">
						<select id="category">
							<?php foreach ($cats as $id => $name) {
								?>
								<option value="<?php echo $id ?>"><?php echo "$name"?></option>
								<?php
							}
							?>
						</select>
					</div>
					<a href="submit.php"><button>Submit New Run</button></a>
					<table>
						<thead>
							<tr>
								<th>Username</th>
								<th>Time (dd:hh:mm:ss)</th>
								<th>Link</th>
							</tr>
						</thead>
					<tbody>
					<?php
					mysqli_data_seek($result, 0);
					while ($row = $result->fetch_row()) {
						echo '<tr class="'. $row[3] . '">';
						$count = 0;
						foreach(array_slice($row, 0, 3) as $r) {
							if ($count == 1) {
								$dtF = date_create('@0');
								$dtT = date_create("@$r");
								echo "<td>" . $dtF->diff($dtT)->format('%D:%H:%I:%S') . "</td>";
							} else if ($count == 2) {
								echo "<td><a href='" . $r . "'>Click to Watch Run<a/></td>";
							} else {
								echo "<td>"	. $r . "</td>";
							}
							$count++;
						}
						echo "</tr>";
					}
					echo "</tbody></table>";
					$result->close();
				} else {
					echo "<p>Error Loading Leaderboard</p>";
				}
				$conn->close();
			} else {
				echo "<p>Error Loading Leaderboard</p>";
			}
		?>
	</body>
</html>
<script src="categories.js"></script>
