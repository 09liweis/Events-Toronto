$(document).ready(function() {
	$.ajax({
		url: 'EventController.php?action=getUserEvents',
		method: 'GET',
		success(res) {
			console.log(res);
			renderCalendar(res);
		}
	});
    
});

function renderCalendar(events) {
	$('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        navLinks: true,
        editable: true,
        events: events
    });
}