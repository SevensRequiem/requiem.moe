function setCookie(key, value, expire) {
    var d = new Date();
    d.setTime(expire);
    var expires = "expires="+d.toUTCString();
    document.cookie = key + "=" + value + ";" + expires + ";secure;path=/;domain=systemspace.link";
}

function getCookie(key) {
    var name = key + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
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
  load_resource: 78.995
  captures_list: 109.605
  LoadShardBlock: 91.312 (3)
  PetaboxLoader3.resolve: 50.251
  PetaboxLoader3.datanode: 117.834 (4)
  esindex: 0.022
  CDXLines.iter: 11.143 (3)
  exclusion.robots.policy: 0.179
  RedisCDXSource: 2.762
  exclusion.robots: 0.19
*/