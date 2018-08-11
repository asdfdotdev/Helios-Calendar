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
	if(isset($hc_googleKey) && $hc_googleKey != ''){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
<?php	if(isset($_GET['com']) && $_GET['com'] == 'detail' && $locLat != '' && $locLon != ''){
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