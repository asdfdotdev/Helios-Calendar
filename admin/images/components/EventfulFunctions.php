<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	function contents($parser, $data){
		global $step;
		global $efID;
		
		if($step == "id"){
			$efID = $data;
		}//end if
		$step = "";
	}//end contents
	
	function startTag($parser, $data, $attrs){
		global $step;
		
		if(strtolower($data) == "id"){
			$step = "id";
			xml_set_character_data_handler($parser, "contents");
		} elseif (strtolower($data) == "error"){
			foreach ($attrs as $key => $value) {
				$efMsg = $value;
			}//end if
			exit("Eventful Submission Failed. Error From Eventful: " . $efMsg . "<br />To resubmit this Event/Location to eventful edit it in Helios, do not add it again or you will have duplicates within your Helios Calendar.");
		}//end if
	}//end startTag
	
	function endTag($parser, $data){
	    //	do nothing
	}//end endTag	?>