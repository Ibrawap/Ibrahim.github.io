jQuery(document).ready(function() {
	
	jQuery('.cf-speakers .span2 a').hover(function() {
		jQuery('span',this).stop().animate({ opacity: 1.0 },200);
	}, function() {
		jQuery('span',this).stop().animate({ opacity: 0.0 },200);
	});;
	
});