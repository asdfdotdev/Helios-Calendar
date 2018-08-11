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
	if(isset($hc_cfg26) && $hc_cfg26 != ''){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
<?php	if(isset($_GET['com']) && $_GET['com'] == 'detail'  && 
			isset($locLat) && $locLat != '' &&
			isset($locLon) && $locLon != ''){
			echo "buildGmap('" . $locLat . "', '" . $locLon . "', '" . str_replace('\'','\\\'',$locTag . "<br />" . $locLink) . "');";
		} elseif(isset($_GET['com']) && $_GET['com'] == 'location') {
			echo "buildGmap();";
		} elseif(isset($locID) && $locID > 0 && $locLat != '' && $locLon != '') {
			echo "buildGmap('" . $locLat . "', '" . $locLon . "', '" . str_replace('\'','\\\'',$locTag . "<br />" . $locLink) . "');";
		}//end if	?>
		//-->
		</script>
<?php
	}//end if	?>