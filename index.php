<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/8/14
	 * Time: 4:53 PM
	 */

	$sqlite = new SQLite3("db.sqlite");
	try {
		if (!$sqlite->exec("CREATE TABLE IF NOT EXISTS reminders(
													id INTEGER PRIMARY KEY NOT NULL,
													class_name TEXT,
                                                    assignment TEXT NOT NULL DEFAULT 'stuff',
                                                    created_year TEXT,
                                                    created_month TEXT,
                                                    created_day TEXT,
                                                    due_year TEXT,
                                                    due_month TEXT,
                                                    due_day TEXT,
                                                    done BOOL)") ||
			!$sqlite->exec("CREATE TABLE IF NOT EXISTS classes(
													id INTEGER PRIMARY KEY NOT NULL,
													class_name TEXT)")
		) {
			die($sqlite->lastErrorMsg());
		}
	} catch (SQLiteException $e) {
		die("Error: ".$e->getMessage());
	}

	$cal_info = cal_info(0);
	$months = $cal_info['months'];
	$year = (int)date("Y");
	$statement = $sqlite->prepare("SELECT * FROM classes");
	$classes = $statement->execute();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Homework Reminders</title>
	<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.css"/>
	<script src="main.js"></script>
	<link rel="stylesheet" href="styles.css"/>
</head>
<body>
<h1>Assignment Reminders</h1>
<label for="assignment">Assignment: </label>
<input id="assignment" type="text"/>
<br/>
<label for="class_name">Class Name: </label>
<!--<input id="class_name" type="text"/>-->
<select name="class_name" id="class_name">
	<?
		while ($class = $classes->fetchArray(SQLITE3_ASSOC)) {
			$class_name = $class['class_name'];
			echo "<option value='$class_name'>$class_name</option>";
		}
	?>
</select>
<br/>
<label for="day_due">Day Due:</label>
<select name="day_due" id="day_due">
	<?
		for ($i = 1; $i <= 31; $i++) {
			echo "<option value='$i'>$i</option>";
		}
	?>
</select>
<br/>
<label for="month_due">Month Due:</label>
<select name="month_due" id="month_due">
	<?
		foreach ($months as $month) {
			echo "<option value='$month'>$month</option>";
		}
	?>
</select>
<br/>
<label for="year_due">Year Due:</label>
<select name="year_due" id="year_due">
	<?
		for ($i = $year; $i < $year + 5; $i++) {
			echo "<option value='$i'>$i</option>";
		}
	?>
</select>
<input type="button" value="Submit new assignment" id="new_assignment"/>
<table>
	<tr>
		<th>Id</th>
		<th>Class Name</th>
		<th>Assignment</th>
		<th>Created</th>
		<th>Due</th>
		<th>Done?</th>
	</tr>
	<?
		$statement = $sqlite->prepare("SELECT * FROM reminders");
		$rows = $statement->execute();
		$i = 0;
		while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
			$i++;
			$row_style_class = $i % 2 ? "odd-row" : "";
			$done = (int)$row["done"] ? "Yes" : "No";
			$due_date = $row['due_year'] . "-" . $row['due_month'] . "-" . $row['due_day'];
			$created_date = $row['created_year'] . "-" . $row['created_month'] . "-" . $row['created_day'];
			?>
			<tr class="<?= $row_style_class; ?>">
				<td><?= $row['id']; ?></td>
				<td><?= $row["class_name"]; ?></td>
				<td><?= $row["assignment"]; ?></td>
				<td><?= $created_date; ?></td>
				<td><?= $due_date; ?></td>
				<td><?= $done; ?></td>
			</tr>
		<?
		}
	?>
</table>
</body>
</html>