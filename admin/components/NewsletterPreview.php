<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
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