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
		<li><a href="<?php echo CalRoot;?>/index.php" class="eventMenu">Home</a></li>
<?php 	if($hc_pubSubmit == 1){	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=submit" class="eventMenu">Submit&nbsp;Event</a></li>
<?php 	}//end if	?>
		<li><a href="<?php echo CalRoot;?>/index.php?com=search" class="eventMenu">Search</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=signup" class="eventMenu">Newsletter</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=hotlist" class="eventMenu">Hot&nbsp;List</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=mobile" class="eventMenu">Mobile</a></li>
		<li><a href="<?php echo CalRoot;?>/index.php?com=rss" class="eventMenu">RSS</a></li>
	</ul>
</div>