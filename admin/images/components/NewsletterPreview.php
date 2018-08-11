<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include('../../events/includes/globals.php');
	include('../../events/includes/code.php');
	include('../../events/includes/connection.php');
	
	if(isset($_GET['pID']) && is_numeric($_GET['pID'])){
		$result = doQuery("SELECT TemplateSource FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . cIn($_GET['pID']));
		if(hasRows($result)){
			echo mysql_result($result,0,0);
		} else {	
			echo $hc_lang_news['InvalidTemplate'];
		}//end if
	} else {
		echo $hc_lang_news['InvalidTemplate'];
	}//end if	?>