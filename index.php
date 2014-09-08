<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 9/8/14
 * Time: 4:53 PM
 */

$sqlite = new SQLite3("db.sqlite");
try {
	if (!$sqlite->exec("CREATE TABLE IF NOT EXISTS reminders(class_name TEXT,
                                                    assignment TEXT NOT NULL DEFAULT 'stuff',
                                                    created TEXT,
                                                    due TEXT,
                                                    done BOOL)")
	) {
		die($sqlite->lastErrorMsg());
	}
} catch (SQLiteException $e) {
	die("Error: $e");
}

$cal_info = cal_info(0);
$months = $cal_info['months'];
$year = (int)date("Y");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Homework Reminders</title>
</head>
<body>
<h1>Assignment Reminders</h1>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="main.js"></script>
<label for="assignment">Assignment: </label>
<input id="assignment" type="text"/>
<br/>
<label for="class_name">Class Name: </label>
<input id="class_name" type="text"/>
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
		<th>Class Name</th>
		<th>Assignment</th>
		<th>Created</th>
		<th>Due</th>
		<th>Done?</th>
	</tr>
	<?
	$statement = $sqlite->prepare("SELECT * FROM reminders");
	$rows = $statement->execute();

	while ($row = $rows->fetchArray(SQLITE3_BOTH)) {
		$done = (int)$row["done"] ? "Yes" : "No";
		?>
		<tr>
			<td><?= $row["class_name"]; ?></td>
			<td><?= $row["Assignment"]; ?></td>
			<td><?= $row["created"]; ?></td>
			<td><?= $row["due"]; ?></td>
			<td><?= $done; ?></td>
		</tr>
	<?
	}
	?>
</table>
</body>
</html>