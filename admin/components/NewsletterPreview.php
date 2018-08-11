<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	
	hookDB();
	
	if(isset($_GET['pID']) && is_numeric($_GET['pID'])){
		$result = doQuery("SELECT TemplateSource FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . cIn($_GET['pID']));
		if(hasRows($result)){
			echo mysql_result($result,0,0);
		} else {	?>
		Invalid Template. Please select a different template.
	<?	}//end if
	} else {	?>
		Invalid Template. Please select a different template.
<?	}//end if	?>