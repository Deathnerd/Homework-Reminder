/**
 * Created by Deathnerd on 9/8/14.
 */
$(document).ready(function () {
	/*
	 * Handle submitting a new assignment
	 */
	$("#new_assignment").click(function () {
		var month_due = $("#month_due").val();
		var year_due = parseInt($("#year_due").val());
		var day_due = parseInt($("#day_due").val());
		var class_name = $("#class_name").val();
		var assignment = $("#assignment").val();
		$.ajax({
			url:     "actions.php",
			data:    {
				action:     "new",
				class_name: class_name,
				assignment: assignment,
				year_due:   year_due,
				month_due:  month_due,
				day_due:    day_due
			},
			success: function (results) {
				alert(results);
				if (results != "Success") {
					return;
				}
				location.reload(true);
			}
		})
	});
	/*
	 * Handle what happens when the user clicks "Complete" on an assignment's row
	 */
	$('.complete_assignment').click(function () {
		var row = $(this).closest('tr');
		var assignment = $(row).find('.assignment').text();
		var class_name = $(row).find('.class_name').text();
		$.ajax({
			url:     "actions.php",
			data:    {
				action:     "done",
				class_name: class_name,
				assignment: assignment,
				/*
				 * Stupid little workaround. TODO: fix actions.php to not need these on every request
				 */
				year_due:   "year_due",
				month_due:  "month_due",
				day_due:    "day_due"
			},
			success: function (results) {
				alert(results);
				if (results != "Success") {
					return;
				}
				$(row).find('.done').text("Yes");
			}
		})
	});
	/*
	 * Handle deleting an assignment
	 */
	$('.delete_assignment').click(function () {
		if (!window.confirm("This will delete the assignment from the database. There is no undoing this! Are you sure?")) {
			return;
		}
		var row = $(this).closest('tr');
		var assignment = $(row).find('.assignment').text();
		var class_name = $(row).find('.class_name').text();
		$.ajax({
			url:     "actions.php",
			data:    {
				action:     "delete",
				class_name: class_name,
				assignment: assignment,
				/*
				 * Stupid little workaround. TODO: fix actions.php to not need these on every request
				 */
				year_due:   "year_due",
				month_due:  "month_due",
				day_due:    "day_due"
			},
			success: function (results) {
				alert(results);
				if (results != "Success") {
					return;
				}
				$(row).remove();
			}
		})
	})
});