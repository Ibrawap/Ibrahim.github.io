var Application = {
	show : function() {
		jQuery('.application-overlay').stop().animate({ top: 40 }, 500);
		jQuery('.cf-ribbon').stop().animate({height: 1000},500);
	},
	hide : function() {
		jQuery('.application-overlay').stop().animate({ top: -1200 }, 500);
		jQuery('.cf-ribbon').stop().animate({height: 200},500);
	}
};

jQuery(document).ready(function() {
	
	jQuery('.cf-speakers .span2 a').hover(function() {
		jQuery('span',this).stop().animate({ opacity: 1.0 },100);
	}, function() {
		jQuery('span',this).stop().animate({ opacity: 0.0 },100);
	});;
	
	jQuery('.apply-now').click(function(e) {
		Application.show();
		
		e.stopPropagation();
		e.preventDefault();
	});

	jQuery('body').click(function(e) {
		var application = jQuery('.application-overlay');
		if( application.has(e.target).length === 0) 
			Application.hide();
	});
	
});