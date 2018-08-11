<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(show,hide1,hide2,hide3){
		document.getElementById(show).style.display = 'block';
		document.getElementById(hide1).style.display = 'none';
		document.getElementById(hide2).style.display = 'none';
		document.getElementById(hide3).style.display = 'none';
		return false;
	}//end toggleMe()
	//-->
	</script>
	<br />
	<fieldset>
		<legend>RSS Event Syndication</legend>
		<a href="<?php echo CalRoot;?>/index.php?com=rss" class="eventMain">Click here for our RSS related resources</a>
	</fieldset>
	<br />
	<fieldset>
		<legend>JavaScript Event Syndication</legend>
		<b>Setup:</b>
		<ol>
			<li>Place Stylesheet code below into the head tag of your site, or <a href="<?php echo CalRoot;?>/js/syndication.css" class="eventMain" target="_blank">create your own</a>.</li>
			<li>Place Syndication code below where you want the event content displayed.</li>
			<li>Edit the variables to customize your event list.</li>
		</ol>
		<div style="line-height:15px;"><b>Stylesheet:</b> [ <a href="<?php echo CalRoot;?>/js/syndication.css" class="eventMain" target="_blank">template</a> ]</div>
		<div class="frmOpt">
			<input name="stylesheet" id="styleshset" style="width:95%;" onfocus="this.select();" value='<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/js/syndication.css" />' />
		</div>
		<br />
		<b>Syndication Code:</b>

			<textarea style="width:95%;height:135px;" onfocus="this.select();" wrap="off" readonly><script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/js/syndication.php?s=0&z=10&t=1">
//<!--
/*	This comment can be removed prior to publishing
	s = Event List Type, 0 = All Events, 1 = Newest Events, 2 = Most Popular Events, 3 = Featured Events
	z = Maximum Number of Events to Display
	t = Show Event Time, 1 = Yes Show Time, 0 = No Hide Time
*/
//-->
</script></textarea>
	</fieldset>
	<br />
	<fieldset>
		<legend>OpenSearch Support</legend>
		We support OpenSearch 1.1. To access our search through your browser click on your search manager and add us.
	</fieldset>
	<br />
	<fieldset>
		<legend>Mobile Site</legend>
		<a href="<?php echo MobileRoot;?>" class="eventMain"><b><?php echo MobileRoot;?></b></a>
		<br /><br />
		It's that easy to take all our event info with you everywhere you go.
		<br />
		(XHTML Mobile Support Requred)
	</fieldset>