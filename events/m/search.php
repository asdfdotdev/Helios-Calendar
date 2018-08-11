<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	$isAction = 1;
	include('../includes/include.php');	

echo "<?xml version=\"1.0\"?>";	?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
	"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName;?> Mobile</title>
</head>
<body>
	<div class="menu">
		<a href="<?php echo CalRoot;?>/m/browse.php" class="mnu">Browse Events</a>
	</div>
	<form method="post" action="<?php echo CalRoot;?>/m/results.php">
	<div class="content">
		Search Text:<br/>
		<input type="text" name="srchText" id="srchText" value="" class="txtInput"/><br/>
		<input type="submit" name="submit" id="submit" value="Submit" class="btnInput"/>
	</div>
	</form>
	<div class="menu">
		<a href="<?php echo CalRoot;?>/m/browse.php" class="mnu">Browse Events</a>
	</div>
	<div class="footer">
		Powered by:<br/>
		Helios Calendar
	<a accesskey="1" href="<?php echo CalRoot;?>/m/browse.php"></a>
	<a accesskey="2" href="<?php echo CalRoot;?>/m/search.php"></a>
	<a accesskey="0" href="<?php echo CalRoot;?>/m/help.php"></a>
	</div>
</body>
</html>