//Howler.mobileAutoEnable = false;

const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);

$(document).ready(function() {
	if (isChrome) {
		var sound = new Howl({
			src: '/res/audio/blank.ogg',
			onplay: function() {
				JumpStart();
			},
			onplayerror: function() {
				console.log("got here!");
			}
		});

		sound.play();
	} else {
		JumpStart();
	}	
});

let jumped = false;
function JumpStart() {
	if (jumped) {
		return;
	}

	jumped = true;
	$(document).trigger('jumped');
}

function Jump(callback) {
	$(document).on('jumped', callback);
}

Jump(function() {
	console.log("i jumped");
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
  captures_list: 237.19
  exclusion.robots: 0.33
  LoadShardBlock: 149.317 (3)
  load_resource: 56.846
  PetaboxLoader3.resolve: 118.564 (2)
  CDXLines.iter: 21.116 (3)
  esindex: 0.016
  RedisCDXSource: 60.233
  PetaboxLoader3.datanode: 77.098 (4)
  exclusion.robots.policy: 0.308
*/