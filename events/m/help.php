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
<?php
	$theDate = date("Y") . "-" . date("m") . "-" . date("d");
	if(isset($_GET['date'])){
		$theDate = $_GET['date'];
	}//end if
	
	$datepart = split("-", $theDate);
	if($theDate == '' || !checkdate($datepart[1], $datepart[2], $datepart[0])){
		$theDate = date("Y") . "-" . date("m") . "-" . date("d");
		$datepart = split("-", $theDate);
	}//end if
	
	$theDate = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));
	if($theDate <= date("Y-m-d")){
		$prevDay = $theDate;
	} else {
		$prevDay = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2] - 1, $datepart[0]));
	}//end if
	$nextDay = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2] + 1, $datepart[0]));
	$dateStamp = date("D M j, Y", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName;?> Mobile</title>
</head>
<body>
	<div class="menu">
		Mobile Site Help
	</div>
	<div class="content">
		<b>Help Topics</b><br/>
		<a href="#accesskeys" class="mnu">Accesskeys</a><br/>
		<a href="#eventsearch" class="mnu">Event Search</a><br/>
		<a href="#fullsite" class="mnu">Full Site</a><br/><br/>
		<a href="<?php echo CalRoot;?>/m/" class="mnu">&laquo;Mobile Home Page</a>
		
		<br/><br/>
		<a id="accesskeys"></a>
		<b>AccessKeys</b><br/>
		You can quickly and easily access areas of our mobile site by pressing the number key associated with where you want to go.
		
		<br/><br/>
		<i>Universal</i><br/>
		<b>1</b> - Browse Events<br/>
		<b>2</b> - Search Events<br/>
		<b>0</b> - This Help File
		
		<br/><br/>
		<i>Browsing Events</i><br/>
		<b>4</b> - Previous Day<br/>
		<b>5</b> - Today<br/>
		<b>6</b> - Next Day
		
		<br/><br/>
		<i>Event Details</i><br/>
		<b>4</b> - Back<br/>
		<b>5</b> - Today
		
		<br/><br/>
		<a id="eventsearch"></a>
		<b>Event Search</b><br/>
		When searching events you will receive up to ten of the next occuring events with a Title or Description containing your keywords.
		Search using multiple keywords to refine your search.
		
		<br/><br/>
		<a id="fullsite"></a>
		<b>Full Site</b><br/>
		You can view all our events and access all our event sharing tools by visiting our website at: <b><?php echo CalRoot;?>/</b>
	</div>
	<div class="menu">
		<a href="<?php echo CalRoot;?>/m/browse.php" class="mnu">Browse</a> &#124;
		<a href="<?php echo CalRoot;?>/m/search.php" class="mnu">Search</a>
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