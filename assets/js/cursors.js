$(document).ready(function() {
	$("input[type='range']").addClass("cur-action");
	
	$("a").each(function(el) {
		var uri = $(this).attr("href");

		if (uri == undefined) {
			return true;
		}

		if (uri !== "#" && uri !== "javascript:void(0);" && !uri.startsWith("mailto:")) {
			var currentDepth = location.pathname.split("/").length - 2;
			var newDepth;

			if (uri.startsWith("/")) {
				newDepth = uri.split("/").length - 2;
			}
			else if (uri.startsWith("http")) {
				newDepth = "Out";
			}
			else {
				newDepth = currentDepth + (uri.split("/").length - 1) - ((uri.split("../").length - 1) * 2);
			}

			var diff = newDepth - currentDepth;

			if (newDepth === "Out") {
				$(this).addClass("cur-out");
			}
			else if (diff < -1) {
				$(this).addClass("cur-downdown");
			}
			else if (diff === -1) {
				$(this).addClass("cur-down");
			}
			else if (diff === 0) {
				$(this).addClass("cur-stay");
			}
			else if (diff === 1) {
				$(this).addClass("cur-up");
			}
			else if (diff > 1) {
				$(this).addClass("cur-upup");
			}
		}
		else {
			if (uri.startsWith("mailto:")) {
				$(this).addClass("cur-out");
			}
			else if ($(this).hasClass("expandable-button")) {
				if ($(this).parents(".expandable").hasClass("open")) {
					$(this).addClass("cur-close");
				} else {
					$(this).addClass("cur-open");
				}
			} else {
				$(this).addClass("cur-action");
			}
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
  esindex: 0.01
  PetaboxLoader3.resolve: 86.865
  exclusion.robots.policy: 0.208
  captures_list: 72.542
  load_resource: 109.727
  CDXLines.iter: 13.596 (3)
  exclusion.robots: 0.224
  PetaboxLoader3.datanode: 48.841 (4)
  LoadShardBlock: 55.342 (3)
  RedisCDXSource: 0.643
*/