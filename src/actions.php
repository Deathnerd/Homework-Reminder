<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/8/14
	 * Time: 5:31 PM
	 */
	require_once("Utilities.php");
	use Utilities\Utilities;

	$Utils = new Utilities();
	$action = $_GET['action'];

	if (!$Utils->checkIsSet(array($_GET['class_name'], $_GET['year_due'], $_GET['month_due'], $_GET['day_due'], $_GET['assignment']),
		array("Class Name not set", "Year Due not set", "Month Due not set", "Day Due not set", "Assignment not set"))
	) {
//		header("Location: index.php");
		exit();
	}

	try {
		$months = array("January"   => "1",
		                "February"  => "2",
		                "March"     => "3",
		                "April"     => "4",
		                "May"       => "5",
		                "June"      => "6",
		                "July"      => "7",
		                "August"    => "8",
		                "September" => "9",
		                "October"   => "10",
		                "November"  => "11",
		                "December"  => "12");
	} catch (Exception $e) {
		exit("Error: " . $e->getMessage());
	}

	header("Content-type: application/text");
	try {
		$db = new SQLite3("db.sqlite");
		switch ($action) {
			case "new":
				$statement = $db->prepare("INSERT INTO reminders (class_name, assignment, created_year, created_month, created_day, due_year, due_month, due_day, done)
										VALUES (:class_name, :assignment, :this_year, :this_month, :today, :year_due, :month_due, :day_due, 0)");
				if(!$statement){
					exit($db->lastErrorMsg());
				}
				$statement->bindValue(":this_year", date('Y'), SQLITE3_TEXT);
				$statement->bindValue(":this_month", date('m'), SQLITE3_TEXT);
				$statement->bindValue(":today", ltrim(date('d'), "0"), SQLITE3_TEXT);
				$statement->bindValue(":year_due", $_GET['year_due'], SQLITE3_TEXT);
				$statement->bindValue(":month_due", $months[$_GET['month_due']], SQLITE3_TEXT);
				$statement->bindValue(":day_due", $_GET['day_due'], SQLITE3_TEXT);
				break;
			case "delete":
				$statement = $db->prepare("DELETE FROM reminders WHERE class_name IS :class_name AND assignment IS :assignment;");
				if(!$statement){
					exit($db->lastErrorMsg());
				}
				break;
			case "done":
				$statement = $db->prepare("UPDATE reminders SET done=1 WHERE class_name IS :class_name AND assignment IS :assignment;");
				if(!$statement){
					exit($db->lastErrorMsg());
				}
				break;
			default:
				exit("Action not set");
		}
		$statement->bindValue(":class_name", $_GET['class_name'], SQLITE3_TEXT);
		$statement->bindValue(":assignment", $_GET['assignment'], SQLITE3_TEXT);
		$statement->execute();
	} catch (SQLiteException $e) {
		die("Error: ".$e->getMessage());
	}

	exit("Success");