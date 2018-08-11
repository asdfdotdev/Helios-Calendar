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
	$isAction = 1;
	include('../includes/include.php');	
	include('overhead.php');

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
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName . " " . $hc_lang_mobile['Mobile'];?></title>
</head>
<body>
	<div class="menu">
		<?php echo $hc_lang_mobile['MobileHelp'];?>
	</div>
	<div class="content">
		<b><?php echo $hc_lang_mobile['HelpTopics'];?></b><br/>
		<a href="#accesskeys" class="mnu"><?php echo $hc_lang_mobile['Accesskeys'];?></a><br/>
		<a href="#eventsearch" class="mnu"><?php echo $hc_lang_mobile['EventSearch'];?></a><br/>
		<a href="#fullsite" class="mnu"><?php echo $hc_lang_mobile['FullSite'];?></a><br/><br/>
		<a href="<?php echo MobileRoot;?>/" class="mnu">&laquo;<?php echo $hc_lang_mobile['Homepage'];?></a>
		
		<br/><br/>
		<a id="accesskeys"></a>
		<b><?php echo $hc_lang_mobile['Accesskeys'];?></b><br/>
		<?php echo $hc_lang_mobile['AccesskeysHelp'];?>
		
		<br/><br/>
		<i><?php echo $hc_lang_mobile['Universal'];?></i><br/>
		<b>1</b> - <?php echo $hc_lang_mobile['Browse'];?><br/>
		<b>2</b> - <?php echo $hc_lang_mobile['Search'];?><br/>
		<b>3</b> - <?php echo $hc_lang_mobile['Language'];?><br/>
		<b>0</b> - <?php echo $hc_lang_mobile['HelpFile'];?>
		
		<br/><br/>
		<i><?php echo $hc_lang_mobile['Browsing'];?></i><br/>
		<b>4</b> - <?php echo $hc_lang_mobile['PreviousDay'];?><br/>
		<b>5</b> - <?php echo $hc_lang_mobile['Today'];?><br/>
		<b>6</b> - <?php echo $hc_lang_mobile['NextDay'];?>
		
		<br/><br/>
		<i><?php echo $hc_lang_mobile['Details'];?></i><br/>
		<b>4</b> - <?php echo $hc_lang_mobile['Back'];?><br/>
		<b>5</b> - <?php echo $hc_lang_mobile['Today'];?>
		
		<br/><br/>
		<a id="eventsearch"></a>
		<b><?php echo $hc_lang_mobile['Search'];?></b><br/>
		<?php echo $hc_lang_mobile['SearchHelp'];?>
		
		
		<br/><br/>
		<a id="fullsite"></a>
		<b><?php echo $hc_lang_mobile['FullSite'];?></b><br/>
		<?php echo $hc_lang_mobile['FullSiteHelp'];?>
		 <b><?php echo CalRoot;?>/</b>
	</div>
	<div class="menu">
		<a href="<?php echo MobileRoot;?>/browse.php" class="mnu"><?php echo $hc_lang_mobile['BrowseMnu'];?></a> &#124;
		<a href="<?php echo MobileRoot;?>/search.php" class="mnu"><?php echo $hc_lang_mobile['SearchMnu'];?></a>
	</div>
	<div class="footer">
		<div>helios&nbsp;<img src="<?php echo CalRoot;?>/images/favicon.png" width="16" height="16" alt="" style="vertical-align:middle;" />&nbsp;calendar</div>
		<a accesskey="1" href="<?php echo MobileRoot;?>/browse.php"></a>
		<a accesskey="2" href="<?php echo MobileRoot;?>/search.php"></a>
		<a accesskey="3" href="<?php echo MobileRoot;?>/lang.php"></a>
		<a accesskey="0" href="<?php echo MobileRoot;?>/help.php"></a>
	</div>
</body>
</html>