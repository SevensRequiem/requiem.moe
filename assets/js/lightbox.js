$(document).ready(function() {
	$("body").append('<div id="lightboxes"></div>');
});

/*

Lightbox(url);

*/

function Lightbox(url) {
	var text = '<div class="lb_fullscreen-popup lightbox-container"><img class="lightbox" src="' + url + '" /></div>';

	$("#lightboxes").append(text);

	var object = $("#lightboxes").children()[$("#lightboxes").children().length - 1];

	$(object).addClass("animate");
	$(object).find(".lightbox").addClass("animate");
	setTimeout(function(){
		$(object).removeClass("animate");
		$(object).find(".lightbox").removeClass("animate");
	}, 500);

	var pressed = false;
	
	$(object).click(function(){
		$(object).addClass("hidden");
		$(object).find(".lightbox").addClass("hidden");
		setTimeout(function(){
			$(object).remove();
		}, 480);
	});
}
/*
     FILE ARCHIVED ON 22:59:51 Nov 28, 2019 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 19:58:30 Dec 21, 2019.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  PetaboxLoader3.resolve: 43.851
  exclusion.robots.policy: 0.213
  load_resource: 92.644
  captures_list: 544.067
  LoadShardBlock: 327.184 (3)
  exclusion.robots: 0.23
  RedisCDXSource: 199.978
  PetaboxLoader3.datanode: 86.181 (4)
  CDXLines.iter: 13.087 (3)
  esindex: 0.012
*/