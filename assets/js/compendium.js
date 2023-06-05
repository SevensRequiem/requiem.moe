$(document).ready(function() {
	$('body').append(`
		<div id="vcmp" data-id="NoID">
			<div id="vcmp-axis1"></div>
	    	<div id="vcmp-axis2"></div>
			<div id="vcmp-close" class="metab">CLOSE</div>
			<div id="vcmp-open" class="metab">OPEN IN COMPENDIUM</div>
			<div id="vcmp-axiscolorize"></div>
			<div id="vcmp-header">No object</div>
			<div id="vcmp-info">from the TSUKI Project Compendium</div>
			<div id="vcmp-data">no data?</div>
		</div>`);

	$('body').on('click', 'vcmp', function() {
		OpenVCMP($(this).attr('rel') || $(this).html());
	});

	$('body').on('click', '#vcmp-close', function() {
		CloseVCMP();
	});

	$('body').on('click', '#vcmp-open', function() {
		GoToVCMP();
	});

	$('body').on('click', '.inlink', function() {
		if ($(this).attr('data-target').startsWith('Glossary!')) {
			OpenVCMP($(this).attr('data-target').replace('Glossary!', ''));
		} else {
			GoToOtherVCMP($(this).attr('data-target'));
		}
	});
});

function OpenVCMP(header) {
	if ($('#vcmp').hasClass('visible')) {
		sfx_compswitch.play();
	} else {
		sfx_compopen.play();
		$('#vcmp').addClass('visible');
	}

	GetVCMP(header);
}

function CloseVCMP() {
	sfx_compclose.play();
	$('#vcmp').removeClass('visible');
}

function GoToVCMP() {
	sfx_compgoto.play();
	const id = $('#vcmp-header').html().replace(/[^0-9a-z]/ig, '');
	window.open('https://web.archive.org/web/20191128225951/https://compendium.systemspace.link/#Glossary!' + id, '_blank').focus();
}

function GoToOtherVCMP(id) {
	sfx_compgoto.play();
	window.open('https://web.archive.org/web/20191128225951/https://compendium.systemspace.link/#' + id, '_blank').focus();
}

function GetVCMP(header) {
	$('#vcmp-header').html(header);
	$('#vcmp-data').html('Loading...');
	$.get(`https://compendium.systemspace.link/api/get-page.php?id=Glossary`, (d) => {
		if (d.error) {
			$('#vcmp-data').html(`${ d.error }`);
			return;
		}

		const eldat = new RegExp('\\<h2[^\>]*\\>\\s?' + header + '\\s?\\<\\/h2\\>([^]+?)(?:(?:\\<h2)|(?:$))').exec(d.page.content);

		if (eldat === null) {
			$('#vcmp-data').html('There\'s no description for this term in the glossary yet. Why not write one?');
			return;
		}

		$('#vcmp-data').html(eldat[1]);
	}).fail(() => {
		$('#vcmp-data').html('Connection error');
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
  CDXLines.iter: 16.634 (3)
  PetaboxLoader3.resolve: 313.65 (3)
  LoadShardBlock: 287.661 (3)
  captures_list: 388.735
  exclusion.robots: 0.39
  esindex: 0.022
  load_resource: 108.79
  RedisCDXSource: 7.603
  exclusion.robots.policy: 0.366
  PetaboxLoader3.datanode: 70.885 (4)
*/