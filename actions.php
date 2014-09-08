<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 9/8/14
 * Time: 5:31 PM
 */

$action = $_GET['action'];

if (!isset($_GET['class_name']) || !isset($_GET['year_due']) || !isset($_GET['month_due']) || !isset($_GET['day_due']) || !isset($_GET['assignment']) ||
	!empty($_GET['class_name']) || !empty($_GET['year_due']) || !empty($_GET['month_due']) || !empty($_GET['day_due']) || !empty($_GET['assignment']) ||
	is_null($_GET['class_name']) || is_null($_GET['year_due']) || is_null($_GET['month_due']) || isset($_GET['day_due']) || is_null($_GET['assignment'])) {
	die("Check your params");
}

header("Content-type: application/text");
try {
	$db = new SQLite3("db.sqlite");
	switch ($action) {
		case "new":
			$statement = $db->prepare("INSERT INTO reminders (class_name, assignment, created, due, done) VALUES (':class_name', ':assignment', ':today-:this_month-:this_year', ':year-:month-:day', 0)");
			break;
		case "delete":
			$statement = $db->prepare("DELETE FROM reminders WHERE class_name=':class_name' AND assignment=':assignment' AND due=':year-:month-:day';");
			break;
		case "done":
			$statement = $db->prepare("UPDATE reminders SET due=1 WHERE class_name=':class_name' AND assignment=':assignment' AND due=':year-:month-:day';");
			break;
		default:
			exit("Action not set");
	}
	$statement->bindValue(":class_name", $_GET['class_name'], SQLITE3_TEXT);
	$statement->bindValue(":assignment", $_GET['assignment'], SQLITE3_TEXT);
	$statement->bindValue(":year", $_GET['year_due'], SQLITE3_INTEGER);
	$statement->bindValue(":month", $_GET['month_due'], SQLITE3_INTEGER);
	$statement->bindValue(":day", $_GET['day_due'], SQLITE3_INTEGER);
	$statement->execute();
} catch (SQLiteException $e) {
	die("Error: $e");
}

exit("Success");