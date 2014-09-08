/**
 * Created by Deathnerd on 9/8/14.
 */
$(document).ready(function () {
	var date = new Date();
	$("#new_assignment").click(function () {
		var month_due = $("#month_due").val();
		var year_due = $("#year_due").val();
		var day_due = $("#day_due").val();
		var class_name = $("#class_name").val();
		var assignment = $("#assignment").val();

		var today = date.getDate();
		var this_month = date.getMonth();
		var this_year = date.getFullYear();

		/*var today_full_date = ""+today+"-"+this_month+"-"+this_year;
		var due_full_date = ""+day_due+"-"+month_due+"-"+year_due;*/

		if (this_year > year_due || (today > day_due && (this_month > month_due || month_due == this_month))) {
			alert("Check yo date, foo");
			return;
		}

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