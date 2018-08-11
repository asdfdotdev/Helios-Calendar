/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
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