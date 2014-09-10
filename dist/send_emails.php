<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/8/14
	 * Time: 11:58 PM
	 */

	require_once("Utilities.php");
	require_once("phpmailer/PHPMailerAutoload.php");
	require_once("mail_config_vals.php");

	$mailer = new PHPMailer(true);
	$db = new SQLite3("db.sqlite");
	$today = date("Y-m-d");

	try {
		$mailer->Host = EMAIL_HOST;
		$mailer->SMTPDebug = 2;
		$mailer->SMTPAuth = true;
		$mailer->SMTPSecure = "tls";
		$mailer->Port = EMAIL_PORT;

		$mailer->Username = EMAIL_USERNAME;
		$mailer->Password = EMAIL_PASSWORD;

		$statement = $db->prepare("SELECT * FROM reminders WHERE NOT done");
		$rows = $statement->execute();

		$assignments = array();
		$i = 0;
		while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
			$assignments[$i]['year_due'] = $row['due_year'];
			$assignments[$i]['month_due'] = $row['due_month'];
			$assignments[$i]['day_due'] = $row['due_day'];
			$assignments[$i]['assignment'] = $row['assignment'];
			$assignments[$i]['class_name'] = $row['class_name'];
			$i++;
		}
//		var_dump($assignments);
		$mailer->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
		$mailer->addAddress(EMAIL_TO, EMAIL_TO_NAME);

		foreach ($assignments as $assignment) {
			$due_date = $assignment['year_due'] . "-" . $assignment['month_due'] . "-" . $assignment['day_due'];
			$days_till_due = (strtotime($due_date) - strtotime($today)) / 60 / 60 / 24;
			$mailer->Subject = "Daily reminder for {$assignment['class_name']}: {$assignment['assignment']}";
			if ($days_till_due > 0) {
				$mailer->msgHTML("<p>Your assignment \"{$assignment['assignment']}\" for \"{$assignment['class_name']}\" is due in <b>$days_till_due</b> days on <b>$due_date</b></p>
								<p><i>- Homework Reminder</i></p>");
			} elseif ($days_till_due == 0) {
				$mailer->msgHTML("<p>Your assignment \"{$assignment['assignment']}\" for \"{$assignment['class_name']}\" is due <b><i>TODAY</i></b></p>
								<p><i>- Homework Reminder</i></p>");
			} else {
				$mailer->msgHTML("<p>Your assignment \"{$assignment['assignment']}\" for \"{$assignment['class_name']}\" is <b>overdue</b> by $days_till_due days</p>
								<p><i>- Homework Reminder</i></p>");
			}
			$mailer->send();
		}
	} catch (phpmailerException $e) {
		trigger_error($e->errorMessage(), E_ERROR);
	} catch (SQLiteException $e) {
		trigger_error($e->getMessage(), E_ERROR);
	} catch (Exception $e) {
		trigger_error($e->getMessage(), E_ERROR);
	}