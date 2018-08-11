/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
function ajxOutput(address, div, CalRoot){document.getElementById(div).innerHTML = '<img src="' + CalRoot + '/images/loading.gif">';var http_request = false;if(window.XMLHttpRequest){http_request = new XMLHttpRequest();if(http_request.overrideMimeType){http_request.overrideMimeType('text/xml');}} else if (window.ActiveXObject){try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {}}}if (!http_request) {document.getElementById(div).innerHTML = 'Cannot create an XMLHTTP instance';return false;}http_request.onreadystatechange = function(){if(http_request.readyState == 4){if(http_request.status == 200){document.getElementById(div).innerHTML = http_request.responseText;} else {document.getElementById(div).innerHTML = 'There was a problem with the request.';}}};address = CalRoot + '/components/' + address;http_request.open('GET', address, true);http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");http_request.send(null);}