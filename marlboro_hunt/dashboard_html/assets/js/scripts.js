$(document).ready(function() { 	
	$('a.hoverslide').hover_transitions({
		background_color_two: "#CC0000",
		show_method: "sliding_doors_horizontal",
		hide_method: "sliding_doors_horizontal",
		cols: 2,
		rows: 1,
		pattern_speed: 0
		
	});
// accordion code
	$(".accordion").accordion({
    	collapsible: true,
        autoHeight: false
	});
// date picker
	$(function() {
		$( "#from" ).datepicker({
		dateFormat:"yy-mm-dd",
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		$( "#to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#to" ).datepicker({
		dateFormat:"yy-mm-dd",
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		}
		});
	});

});
//tabs
$(function() {
	$("#tabs").organicTabs({
		"speed": 200
	});

});	