function copyToClipboard(element) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val(element).select();
	document.execCommand("copy");
	$temp.remove();
	sfx_copy.play();
	Pop('"' + element + '" copied!');
}

$(document).ready(function() {
	$(".switch-option").click(function() {
		sfx_select.play();

		$(this).parents(".switch").find(".selected").removeClass("selected");
		$(this).addClass("selected");
	});

	$('input[type=range]').on('input', function () {
	    $(this).trigger('change');
	});

	$('input[type=range]').on('mousedown', function () {
		sfx_click.play();
	});
	
	$(".dataverse-link").click(function() {
		sfx_dvout.play();
		var that = this;

		$("html").css("background-image", "url(/res/img/bg/space.png)");
		$("html").css("background-size", "cover");
		$("html").css("background-attachment", "fixed");
		$("html").css("overflow", "hidden");
		$("body").css("background", "#000");

		$("body").css("transition", "2.5s transform ease-out, 2.5s background");
		setTimeout(function() {
			$("body").css("background", "rgba(0, 0, 0, 0.2)");
			$("body").css("transform", "scale(0)");
		}, 5);
		

		setTimeout(function() {
			window.location = "/content/tsuki/dataverses/" + $(that).attr("data-link") + ".php";
		}, 2500);
	});

	$(window).scroll(function() {
	    if ($(window).scrollTop() + $(window).height() == $(document).height()) {
	        $("footer").addClass("scrolled");
	    } else {
	        $("footer").removeClass("scrolled");
	    }
	});

	if ($(window).scrollTop() + $(window).height() == $(document).height()) {
        $("footer").addClass("scrolled");
    } else {
        $("footer").removeClass("scrolled");
    }
});

function randInt(min, max) { // min inclusive, max exclusive
	return Math.floor(Math.random() * (max - min)) + min;
}

function timeConverter(unix){
	var a = new Date(unix);
	var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year = a.getFullYear();
	var month = months[a.getMonth()];
	var date = a.getDate();
	var hour = a.getHours();
	var min = a.getMinutes();
	var sec = a.getSeconds();
	var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + (min < 10 ? "0" : "") + min + ':' + (sec < 10 ? "0" : "") + sec;
	return time;
}

function getQueryVariable(variable) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if(pair[0] == variable){
			return pair[1];
		}
	}
	return(false);
}

String.prototype.hashCode = function() {
	var hash = 0, i, chr;
	if (this.length === 0) return hash;
	for (i = 0; i < this.length; i++) {
		chr   = this.charCodeAt(i);
		hash  = ((hash << 5) - hash) + chr;
		hash |= 0; // Convert to 32bit integer
	}
	return hash;
};

function setPseudo(elem, property, value) {
	var hash = (elem+property).hashCode();

	if ($("#x-pseudo-" + hash).length === 0) {
		$("head").append("<style id=\"x-pseudo-" + hash + "\"></style>");
	}

	$("#x-pseudo-" + hash).html(elem + " { " + property + ": " + value + "; }");
}

function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range,
        textInputRange, len, endRange;

    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
        range = document.selection.createRange();

        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");

            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());

            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);

            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;

                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }

    return {
        start: start,
        end: end
    };
}
/*
     FILE ARCHIVED ON 22:59:50 Nov 28, 2019 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 19:58:30 Dec 21, 2019.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  exclusion.robots.policy: 0.183
  PetaboxLoader3.datanode: 72.776 (4)
  PetaboxLoader3.resolve: 107.297
  exclusion.robots: 0.196
  captures_list: 82.331
  RedisCDXSource: 1.975
  load_resource: 123.157
  esindex: 0.008
  CDXLines.iter: 17.003 (3)
  LoadShardBlock: 59.408 (3)
*/