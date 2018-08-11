<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$isAction = 1;
	include('../includes/include.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
		
	if(isset($_SESSION['hc_cap'])){
		$capEntered = "";
		if(isset($_GET['capEntered'])){
			$capEntered = $_GET['capEntered'];
		}//end if
		
		echo $_SESSION['hc_cap'] == $capEntered ? 
			"<span style=\"color:#008000;\">" . $hc_lang_register['Correct'] . "</span>&nbsp;&nbsp;" :
			"<span style=\"color:#DC143C;\">" . $hc_lang_register['Incorrect'] . "</span>&nbsp;";
		echo "<a href=\"javascript:;\" onclick=\"testCAPTCHA();\" class=\"eventMain\">" . $hc_lang_register['ConfirmAgain'] . "</a>";
	} else {
		echo $hc_lang_register['RefreshPage'];
	}//end if