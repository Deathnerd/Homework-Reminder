<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/8/14
	 * Time: 4:53 PM
	 */

	try {
		$sqlite = new SQLite3("db.sqlite");

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
		die("Error: " . $e->getMessage());
	} catch (Exception $e){
		die("Error: " . $e->getMessage());
	}

	$cal_info = cal_info(0);
	$months = $cal_info['months'];
	$statement = $sqlite->prepare("SELECT * FROM classes");
	$classes = $statement->execute();

	list($year, $this_month, $today) = explode("-", date("Y-m-d"));
	require_once("header.php");
?>
	<form>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<label for="assignment">Assignment: </label>
				<input id="assignment" type="text" class="form-control" placeholder="Enter assignment here"/>
			</div>
			<!--		<br/>-->
			<div class="form-group">
				<label for="class_name">Class Name: </label>
				<select name="class_name" id="class_name" class="form-control">
					<?
						while ($class = $classes->fetchArray(SQLITE3_ASSOC)) {
							$class_name = $class['class_name'];
							echo "<option value='$class_name'>$class_name</option>";
						}
					?>
				</select>
			</div>
			<!--		<br/>-->
			<div class="form-group">
				<label for="day_due">Day Due:</label>
				<select name="day_due" id="day_due" class="form-control">
					<?
						for ($i = 1; $i <= 31; $i++) {
							$selected_text = $i == $today ? "selected" : "";
							echo "<option value='$i' $selected_text>$i</option>";
						}
					?>
				</select>
			</div>
		</div>
		<!--		<br/>-->
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<label for="month_due">Month Due:</label>
				<select name="month_due" id="month_due" class="form-control">
					<?
						$j = 0;
						foreach ($months as $month) {
							$selected_text = $month == $months[(int)$this_month] ? "selected" : "";
							echo "<option value='$month' month_array='{$months_array[$j]}' $selected_text>$month</option>";
							$j++;
						}
					?>
				</select>
			</div>
			<!--		<br/>-->
			<div class="form-group">
				<label for="year_due">Year Due:</label>
				<select name="year_due" id="year_due" class="form-control">
					<?
						for ($i = $year; $i < $year + 5; $i++) {
							$selected_text = $i == $today ? "selected" : "";
							echo "<option value='$i'>$i</option>";
						}
					?>
				</select>
			</div>
				<label for="new_assignment"></label>
			<input type="button" value="Submit new assignment" id="new_assignment" name="new_assignment"
			       class="btn btn-success form-control"/>
		</div>
	</form>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
			<tr class="row">
				<th>Assignment</th>
				<th>Class Name</th>
				<th>Created</th>
				<th>Due</th>
				<th>Done?</th>
			</tr>
			</thead>
			<?
				$statement = $sqlite->prepare("SELECT * FROM reminders");
				$rows = $statement->execute();
				$i = 0;
				while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
					$i++;
					$row_style_class = $i % 2 ? "" : "";
					$done = (int)$row["done"] ? "Yes" : "No";
					$due_date = $row['due_year'] . "-" . $row['due_month'] . "-" . $row['due_day'];
					$created_date = $row['created_year'] . "-" . $row['created_month'] . "-" . $row['created_day'];
					?>
					<tr class="<?= $row_style_class; ?> row">
						<td class="assignment"><?= $row["assignment"]; ?></td>
						<td class="class_name"><?= $row["class_name"]; ?></td>
						<td class="created_date"><?= $created_date; ?></td>
						<td class="due_date"><?= $due_date; ?></td>
						<td class="done"><?= $done; ?></td>
						<td>
							<span class="btn btn-success complete_assignment">Complete</span>
						</td>
						<td>
							<span class="btn btn-danger delete_assignment">Delete</span>
						</td>
					</tr>
				<?
				}
			?>
		</table>
	</div>
<? require_once("footer.php");