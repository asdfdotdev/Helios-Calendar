<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	if(isset($hc_googleKey) && $hc_googleKey != '' && isset($_GET['com'])){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
<?php	if($_GET['com'] == 'detail' && $locLat != '' && $locLon != ''){	?>
			buildGmap('<?php echo $locLat;?>', '<?php echo $locLon;?>', '<?php echo $locTag . "<br />" . $locLink;?>');
<?php	} elseif($_GET['com'] == 'location') {	?>
			buildGmap();
<?php 	}//end if	?>
		//-->
		</script>
<?php
	}//end if	?>