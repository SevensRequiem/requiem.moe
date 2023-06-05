$(document).ready(function() {
	$("body").append('<div id="dialogs"></div>');
	$("body").append('<div id="jin-pop" class="hidden">Test pop</div>');
});

/*

Dialog(function(data) {
	if (data === "1") {
		//Do a thing
	}
}, "question",
"Do a thing?",
"Are you sure you wish to do a thing?",
"No, cancel", false,
"Yes, do the thing", true);

*/

var popTimer = null;
function Pop(text, time = 1000) {
	$("#jin-pop").html(text);
	$("#jin-pop").removeClass("hidden");

	if (popTimer !== null) {
		clearTimeout(popTimer);
		popTimer = null;
	}
	popTimer = setTimeout(function() {
		$("#jin-pop").addClass("hidden");
		popTimer = null;
	}, time);
}

function Dialog(callback, type, title, message, button0, button0isconfirm, button1 = null, button1isconfirm = null, button2 = null, button2isconfirm = null) {
	var text = '<div class="fullscreen-popup dialog-container"><div class="dialog"><img class="dialog-icon" src="/res/img/dialog/' + type + '.png" /><h1 class="dialog-title">' + title + '</h1><p class="dialog-text">' + message + '</p><div class="dialog-buttons">';

	if (button0 !== null) {
		text += '<div class="dialog-button';

		if (button0isconfirm) { text += ' confirm'; }

		text += '" id="dialog-button-0">' + button0 + '</div>';
	}

	if (button1 !== null) {
		text += '<div class="dialog-button';

		if (button1isconfirm) { text += ' confirm'; }

		text += '" id="dialog-button-1">' + button1 + '</div>';
	}

	if (button2 !== null) {
		text += '<div class="dialog-button';

		if (button2isconfirm) { text += ' confirm'; }

		text += '" id="dialog-button-2">' + button2 + '</div>';
	}

	text += '</div></div></div>';

	$("#dialogs").append(text);

	sfx_dialog.play();

	var object = $("#dialogs").children()[$("#dialogs").children().length - 1];

	$(object).addClass("animate");
	$(object).find(".dialog").addClass("animate");
	setTimeout(function(){
		$(object).removeClass("animate");
		$(object).find(".dialog").removeClass("animate");
	}, 500);

	var pressed = false;
	
	$(object).find(".dialog-button").click(function(){
		if (!pressed) {
			$(object).addClass("hidden");
			$(object).find(".dialog").addClass("hidden");
			setTimeout(function(){
				$(object).remove();
			}, 480);

			if ($(this).hasClass("confirm")) {
				sfx_dialog_confirm.play();
			} else {
				sfx_dialog_cancel.play();
			}

			callback($(this).attr("id").split("-")[2]);
		}
		pressed = true;
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
  CDXLines.iter: 18.299 (3)
  captures_list: 275.791
  exclusion.robots.policy: 0.291
  PetaboxLoader3.datanode: 309.526 (4)
  RedisCDXSource: 0.755
  PetaboxLoader3.resolve: 28.378
  load_resource: 92.043
  exclusion.robots: 0.313
  esindex: 0.02
  LoadShardBlock: 251.639 (3)
*/