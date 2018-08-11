/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
function ajxOutput(address, div, url){document.getElementById(div).innerHTML = '<img src="'+url+'/img/loading.gif" class="loading" >';var http_request = false;if(window.XMLHttpRequest){http_request = new XMLHttpRequest();if(http_request.overrideMimeType){http_request.overrideMimeType('text/xml');}} else if (window.ActiveXObject){try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {}}}if (!http_request) {document.getElementById(div).innerHTML = 'Cannot create an XMLHTTP instance';return false;}http_request.onreadystatechange = function(){if(http_request.readyState == 4){if(http_request.status == 200){document.getElementById(div).innerHTML = http_request.responseText;} else {document.getElementById(div).innerHTML = 'There was a problem with the request.';}}};http_request.open('GET', address, true);http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");http_request.send(null);}
function validEmail(which,error){var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;if(!filter.test(which.value)){which.className = 'error';return error;}return "";}
function validURL(which,error){var filter = /^(http(s?)\:\/\/|~\/|\/)([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((\/?\w+\/)+|\/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?/;if(!filter.test(which.value)){which.className = 'error';return error;}return "";}
function chkTinyMCE(which,error){if(which == '')return error;return "";}
function validDate(date,format,error){if(!isDate(date.value,format)){date.className = 'error';return error;}return "";}
function validDateBefore(d1,d2,format,error){if(compareDates(d1,format,d2,format) == 1){return error;}return "";}
function validGreater(which,compare,error){if(which.value <= compare){which.className = 'error';return error;}return "";}
function validEqual(which,compare,error){if(which.value != compare.value){which.className = 'error';compare.className = 'error';return error;}return "";}
function validNotEqual(which,compare,error){if(which.value == compare.value){which.className = 'error';return error;}return "";}
function reqField(which,error){if(which.value == ''){which.className = 'error';return error;}return "";}
function validNumber(which,error){if(isNaN(which.value)){which.className = 'error';return error;}return "";}
function validNumberRange(which,min,max,error){if(which.value > max || which.value < min){which.className = 'error';return error;}return "";}
function validPassword(which,which2,error){if(!which.value == which2.value){which.className = 'error';return error;}return "";}
function validMinLength(which,size,error){if(which.value.length < size){which.className = 'error';return error;}return "";}
function validMaxLength(which,size,error){if(which.value.length > size){which.className = 'error';return error;}return "";}
function valid_ok(button,msg){button.disabled = true;button.value = msg;}
function togThis(check,toggle){toggle.disabled = (check.value == 1 || check.checked == true) ? false : true;}
function togArray(opts,active){for(var i in opts){document.getElementById(opts[i]).style.display = (active == opts[i]) ? 'block':'none';}}
function validCheckArray(frm,boxes,myMin,error){var cnt = 0;if(document[frm][boxes].length == undefined){if(document[frm][boxes].checked == true)cnt++;} else {for(i=0;i<document[frm][boxes].length;i++){if(document[frm][boxes][i].checked===true)cnt++;}}if(cnt < myMin)return error;return "";}
function checkAllArray(whichForm, whichCheckBoxArray){if(document[whichForm][whichCheckBoxArray].length == undefined){document[whichForm][whichCheckBoxArray].checked=true} else {for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){document[whichForm][whichCheckBoxArray][i].checked=true}}}
function uncheckAllArray(whichForm, whichCheckBoxArray){if(document[whichForm][whichCheckBoxArray].length == undefined){document[whichForm][whichCheckBoxArray].checked=false} else {for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){document[whichForm][whichCheckBoxArray][i].checked=false}}}
function checkUpdateString(whichForm, whichCheckBoxArray){var checkedString = '';var cnt = 0;if(document[whichForm][whichCheckBoxArray].length == undefined){if(document[whichForm][whichCheckBoxArray].checked == true){checkedString = escape(document[whichForm][whichCheckBoxArray].value);}} else {for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){if(document[whichForm][whichCheckBoxArray][i].checked==true){if(cnt > 0){checkedString = checkedString + ','};checkedString = checkedString + escape(document[whichForm][whichCheckBoxArray][i].value);cnt++;}}}return checkedString;}