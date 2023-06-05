$(document).ready(function() {
	$(".expandable-button").click(function() {
		var p = $(this).parents(".expandable");
		if (p.hasClass("open")) {
			p.removeClass("open");
			$(this).addClass("cur-open");
			$(this).removeClass("cur-close");
		} else {
			p.addClass("open");
			$(this).addClass("cur-close");
			$(this).removeClass("cur-open");
		}
	});
});
/*
     FILE ARCHIVED ON 22:59:50 Nov 28, 2019 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 19:58:30 Dec 21, 2019.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  load_resource: 63.436
  captures_list: 151.699
  LoadShardBlock: 137.104 (3)
  PetaboxLoader3.resolve: 49.116
  PetaboxLoader3.datanode: 136.794 (4)
  esindex: 0.01
  CDXLines.iter: 11.388 (3)
  exclusion.robots.policy: 0.139
  RedisCDXSource: 0.58
  exclusion.robots: 0.149
*/