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
	$searchTxt = "";
	if(isset($_POST['srchText'])){
		$searchTxt = $_POST['srchText'];
	}//end if
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<link rel="apple-touch-icon" href="<?php echo CalRoot;?>/images/appleIcon.png" type="image/png" />
	<title><?php echo CalName . " " . $hc_lang_mobile['Mobile'];?></title>
</head>
<body>
	<div class="menu">
		<?php echo "<a href=\"" . MobileRoot . "/search.php\" class=\"mnu\">" . $hc_lang_mobile['SearchAgain'] . "</a>";?>
	</div>
	<div class="content">
	<?php
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events 
							WHERE IsActive = 1 AND IsApproved = 1 
							AND StartDate >= '" . date("Y-m-d") . "'
							AND MATCH(Title,LocationName,Description) AGAINST('" . cIn(str_replace("'", "\"", $searchTxt)) . "' IN BOOLEAN MODE)
							ORDER BY StartDate, TBD, StartTime, Title LIMIT 15");
		if(hasRows($result)){
			$curDate = "";
			while($row = mysql_fetch_row($result)){
				if($row[9] != $curDate){
					$curDate = $row[9];
					echo "<div class=\"eventDate\">" . cleanXMLChars(stampToDate($row[9],$hc_dateFormat)) . "</div>";
				}//end if
				if($row[10] != ''){
					$timepart = split(":", $row[10]);
					$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				} else {
					$startTime = "";
				}//end if
				
				if($row[11] == 0){
					$timeStamp = $startTime;
				} elseif($row[11] == 1) {
					$timeStamp = "<i>" . $hc_lang_mobile['AllDay'] . "</i>";
				} elseif($row[11] == 2) {
					$timeStamp = "<i>" . $hc_lang_mobile['TBD'] . "</i>";
				}//end if	?>
				<div class="eventLink">
					<a href="<?php echo MobileRoot;?>/details.php?eID=<?php echo $row[0];?>"><?php echo cleanXMLChars(strip_tags($timeStamp)) . "--" . cleanXMLChars(strip_tags($row[1]));?></a>
				</div>
	<?php
			}//end while	
		} else {
			echo "<div align=\"center\"><b>" . $hc_lang_mobile['NoSearch'] . "</b></div>";
		}//end if	?>
	</div>
	<div class="menu">
		<a href="<?php echo MobileRoot;?>/browse.php" class="mnu"><?php echo $hc_lang_mobile['Browse'];?></a>
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