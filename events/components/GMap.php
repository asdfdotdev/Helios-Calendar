<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	if(isset($hc_googleKey) && $hc_googleKey != '' && isset($_GET['com']) && $_GET['com'] == 'detail'){	
		if($locLat != '' && $locLon != ''){?>
		<script language="JavaScript" type="text/JavaScript">
			buildGmap('<?echo $locLat;?>', '<?echo $locLon;?>', '<?echo $locTag . "<br />" . $locLink;?>');
		</script>
	<?	}//end if
	}//end if	?>