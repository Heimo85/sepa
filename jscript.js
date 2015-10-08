$(document).ready( function() {
	$('#drawers').find('h5').click(function(){
	$(this).next().slideToggle();
	$("#drawers div").not($(this).next()).slideUp();
	});

	$('#drawers').find('h5').first().next().slideToggle();

	$(':button').click(function(){
		$(this).closest('div').slideToggle();
		$(this).closest('input').css("background-color", "yellow");
	});
});