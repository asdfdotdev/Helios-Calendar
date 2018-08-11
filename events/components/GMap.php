<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
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