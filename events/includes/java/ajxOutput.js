/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
function ajxOutput(address, div, CalRoot) {document.getElementById(div).innerHTML = '<img src="' + CalRoot + '/images/loading.gif">';var http_request = false;if(window.XMLHttpRequest){http_request = new XMLHttpRequest();if(http_request.overrideMimeType){http_request.overrideMimeType('text/xml');}} else if (window.ActiveXObject){try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {}}}if (!http_request) {document.getElementById(div).innerHTML = 'Cannot create an XMLHTTP instance';return false;}http_request.onreadystatechange = function(){ if(http_request.readyState == 4){if(http_request.status == 200){document.getElementById(div).innerHTML = http_request.responseText;} else {document.getElementById(div).innerHTML = 'There was a problem with the request.';}}};address = CalRoot + '/components/' + address;http_request.open('GET', address, true);http_request.send(null);}