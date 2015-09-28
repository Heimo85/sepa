$(document).ready( function() {
	$('#drawers').find('h5').click(function(){
	$(this).next().slideToggle();
	$("#drawers div").not($(this).next()).slideUp();
	});
});