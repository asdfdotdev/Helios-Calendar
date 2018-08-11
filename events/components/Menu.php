<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<div id="menu">
	<ul>
		<li><a href="<?php echo CalRoot;?>/index.php" class="eventMenu">Events</a></li>
<?php 	if(isset($hc_googleKey) && $hc_googleKey != ''){	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=location" class="eventMenu">Locations</a></li>
<?php 	}//end if
		if($hc_pubSubmit == 1){	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=submit" class="eventMenu">Submit Event</a></li>
<?php 	}//end if	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=search" class="eventMenu">Search</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=signup" class="eventMenu">Newsletter</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=tools" class="eventMenu">Tools</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=rss" class="eventMenu">RSS</a></li>
	</ul>
</div>