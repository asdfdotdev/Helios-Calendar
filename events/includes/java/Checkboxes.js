/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
function validateCheckArray(whichForm,whichCheckBoxArray,myMin){
	var _countChecked = 0;
	var dirty = 0;
	if(document[whichForm][whichCheckBoxArray].length == undefined){
		if(document[whichForm][whichCheckBoxArray].checked == true){
			_countChecked++;
		}//end if
	} else {
		for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){
			if(document[whichForm][whichCheckBoxArray][i].checked===true){
				_countChecked++; 
			}//end if
		}//end for
	}//end if
	if(_countChecked < myMin){
		dirty = 1;
	}//end if
	return dirty;
}//end validateCheckArray()

function checkAllArray(whichForm, whichCheckBoxArray){
	if(document[whichForm][whichCheckBoxArray].length == undefined){
		document[whichForm][whichCheckBoxArray].checked=true
	} else {
		for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){
			document[whichForm][whichCheckBoxArray][i].checked=true
		}//end for
	}//end if
}//end checkAllArray()

function uncheckAllArray(whichForm, whichCheckBoxArray){
	if(document[whichForm][whichCheckBoxArray].length == undefined){
		document[whichForm][whichCheckBoxArray].checked=false
	} else {
		for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){
			document[whichForm][whichCheckBoxArray][i].checked=false
		}//end for
	}//end if
}//end uncheckAllArray()

function checkUpdateString(whichForm, whichCheckBoxArray){
	var checkedString = '';
	var cnt = 0;
	if(document[whichForm][whichCheckBoxArray].length == undefined){
		if(document[whichForm][whichCheckBoxArray].checked == true){
			checkedString = document[whichForm][whichCheckBoxArray].value;
		}//end if
	} else {
		for(i=0;i<document[whichForm][whichCheckBoxArray].length;i++){
			if(document[whichForm][whichCheckBoxArray][i].checked==true){
				if(cnt > 0){checkedString = checkedString + ','};
				checkedString = checkedString + document[whichForm][whichCheckBoxArray][i].value;
				cnt++;
			}//end if
		}//end for
	}//end if	
	return checkedString;
}//end checkUpdateString()