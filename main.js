/**
 * Created by Deathnerd on 9/8/14.
 */
$(document).ready(function () {
	var date = new Date();
	$("#new_assignment").click(function () {
		var month_due = $("#month_due").val();
		var year_due = parseInt($("#year_due").val());
		var day_due = parseInt($("#day_due").val());
		var class_name = $("#class_name").val();
		var assignment = $("#assignment").val();

		$.ajax({
			url: "actions.php",
			data: {
				action: "new",
				class_name : class_name,
				assignment: assignment,
				year_due: year_due,
				month_due: month_due,
				day_due: day_due
			},
			success: function(results){
				alert(results);
				if(results != "Success"){
					alert(results);
					return;
				}
				location.reload(true);
			}
		})
	});
});