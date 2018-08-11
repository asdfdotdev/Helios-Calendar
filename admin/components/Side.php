<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	if(count($hc_Side) > 0){
		echo '<div id="Side">';
		echo '<div class="sideHeader">&nbsp;</div>';
		foreach($hc_Side as $val){
			echo '<a href="' . $val[0] . '" class="side"';
			echo ($val[3] == 1) ? ' target="_blank"' : '';
			echo '>&nbsp;<img src="' . CalAdminRoot . '/images/icons/' . $val[1] . '" width="16" height="16" />&nbsp;' . $val[2] . '</a>';
		}//end foreach
		echo '</div>';
	}//end if
?>