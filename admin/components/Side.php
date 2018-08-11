<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(count($hc_Side) > 0){
		echo '<div id="Side">';
		echo '<div class="sideHeader">&nbsp;</div>';
		foreach($hc_Side as $val){
			echo '<a href="' . $val[0] . '" class="side"';
			echo ($val[3] == 1) ? ' target="_blank"' : '';
			echo '>&nbsp;<img src="' . CalAdminRoot . '/images/icons/' . $val[1] . '" width="16" height="16" alt="" />&nbsp;' . $val[2] . '</a>';
		}//end foreach
		echo '</div>';
	}//end if
?>