$(document).ready(function() {
	$("section").each(function(el) {
		if (typeof $(this).attr("data-section-color") !== typeof undefined && $(this).attr("data-section-color") !== false) {
			$(this)[0].style.setProperty("--section-color", $(this).attr("data-section-color"));
		}
	});

	$(".stylize-section section").each(function(el) {
		$(this)[0].style.setProperty("--section-color", "hsl(" + (170 + (Math.random() * 75)) + ", " + (35 + (Math.random() * 30)) + "%, 55%)");
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
  exclusion.robots.policy: 0.25
  PetaboxLoader3.datanode: 94.635 (4)
  load_resource: 12.31
  exclusion.robots: 0.266
  captures_list: 126.983
  RedisCDXSource: 23.668
  esindex: 0.017
  CDXLines.iter: 12.984 (3)
  LoadShardBlock: 86.323 (3)
*/