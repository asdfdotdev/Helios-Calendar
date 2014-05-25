<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	echo '
	var freq = 300000;

	function toggleMe(who){who.style.display == "none" ? who.style.display = "block":who.style.display = "none";return false;}
	function follow_up(){toggleMe(document.getElementById("follow-up"));var inputs = (document.getElementById("follow-up").style.display == "none") ? true : false;document.getElementById("follow_up").disabled = inputs;document.getElementById("follow_note").disabled = inputs;if(!inputs){document.getElementById("follow_note").focus();scroll(0,0);}}
	function do_favicon(address){var http_request = false;if(window.XMLHttpRequest){http_request = new XMLHttpRequest();if(http_request.overrideMimeType){http_request.overrideMimeType("text/xml");}} else if (window.ActiveXObject){try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {}}}if (!http_request) {return false;}http_request.onreadystatechange = function(){if(http_request.readyState == 4){if(http_request.status == 200){var status = http_request.responseText;var use_ico = (status == "1") ? "favicon_e.ico" : "favicon.ico";var link = document.createElement("link");link.type = "image/x-icon";link.rel = "shortcut icon";link.href = "'.AdminRoot.'/" + use_ico;document.getElementsByTagName("head")[0].appendChild(link);}}};http_request.open("GET", address, true);http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");http_request.send(null);setTimeout("do_favicon(\''.AdminRoot.'/check.php?go=1\')",freq);}
	function fade_it(obj,stall){if(obj.style.display == "none"){return false;}var visible = parseInt(obj.style.opacity * 100);if(visible <= 0){obj.style.display = "none";}setTimeout(function(){if(visible > 0 && stall <= 0){visible = parseFloat(visible - 4);obj.style.opacity = parseFloat(visible/100);}stall--;fade_it(obj,stall);}, 50);}
	window.onload = function(){do_favicon("'.AdminRoot.'/check.php?go=1",freq);if(document.getElementById("hc_dialog")){fade_it(document.getElementById("hc_dialog"),50);}}';
?>