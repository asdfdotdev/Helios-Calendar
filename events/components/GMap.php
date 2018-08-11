<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	if(isset($hc_googleKey) && $hc_googleKey != ''){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
<?php	if(isset($_GET['com']) && $_GET['com'] == 'detail' && $locLat != '' && $locLon != ''){
			echo "buildGmap('" . $locLat . "', '" . $locLon . "', '" . $locTag . "<br />" . $locLink . "');";
		} elseif(isset($locID) && $locID > 0 && $locLat != '' && $locLon != '') {
			echo "buildGmap('" . $locLat . "', '" . $locLon . "', '" . $locTag . "<br />" . $locLink . "');";
		} elseif(isset($_GET['com']) && $_GET['com'] == 'location') {
			echo "buildGmap();";
		}//end if	?>
		//-->
		</script>
<?php
	}//end if	?>