<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	echo '
	function splitLocation(str){
		var locParts = str.split("|");
		setLocation(locParts[0],"",0);
	}
	function togLocation(){
		var input = (document.getElementById("locPreset").value == 0) ? false : true;

		document.getElementById("locName").disabled = input;
		document.getElementById("locAddress").disabled = input;
		document.getElementById("locAddress2").disabled = input;
		document.getElementById("locCity").disabled = input;
		if(document.getElementById("locState"))
			document.getElementById("locState").disabled = input;
		document.getElementById("locZip").disabled = input;
		document.getElementById("locCountry").disabled = input;
		
		if(!input){
			document.getElementById("custom").style.display = "block";
			document.getElementById("custom_notice").style.display = "none";
			if(document.getElementById("locSetting"))
				document.getElementById("locSetting").style.display = "none";
			if(document.getElementById("locSearch"))
				document.getElementById("locSearch").style.display = "block";
			if(document.getElementById("locSelect"))
				document.getElementById("locSelect").style.display = "block";
			document.getElementById("locSearchText").focus();
		} else {
			document.getElementById("custom").style.display = "none";
			document.getElementById("custom_notice").style.display = "block";
		}
	}
	function searchLocations(page){
		if(document.getElementById("locSearchText").value.length > 3)
			ajxOutput("'.CalRoot.'/location-search.php?q="+escape(document.getElementById("locSearchText").value)+"'.((isset($pub_only)) ? '&po='.$pub_only:'').((isset($evnt_only)) ? '&eo='.$evnt_only:'').'&o="+ page, "loc_results", "'.CalRoot.'");
	}
	function setLocation(id,name,search){
		document.getElementById("locPreset").value = id;
		document.getElementById("locPresetName").value = name;
		if((id == 0) && (search == 1)){
			if(document.getElementById("loc_results")){
				document.getElementById("loc_results").innerHTML = "";
				document.getElementById("locSearchText").value = "";
			}
		}
		if(document.getElementById("tweetThis"))
			buildT();
			
		togLocation();
	}';
?>