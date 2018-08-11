<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	include('includes/header.php');
	$searchTxt = (isset($_POST['srchText'])) ? $_POST['srchText'] : '';	?>
</head>
<body>
<div id="header"><?php echo $hc_lang_mobile['SearchResults'];?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/search.php">&laquo;&nbsp;<?php echo $hc_lang_mobile['Search'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/browse.php" class="mnu"><?php echo $hc_lang_mobile['Browse'];?></a>
</div>

<div id="content">
	<ul>
<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events 
						WHERE IsActive = 1 AND IsApproved = 1 
						AND StartDate >= '" . date("Y-m-d") . "'
						AND MATCH(Title,LocationName,Description) AGAINST('" . cIn(str_replace("'", "\"", $searchTxt)) . "' IN BOOLEAN MODE)
						ORDER BY StartDate, TBD, StartTime, Title LIMIT 15");
	if(hasRows($result)){
		$curDate = '';
		while($row = mysql_fetch_row($result)){
			if($row[9] != $curDate){
				$curDate = $row[9];
				echo '<li class="date"><b>' . cleanXMLChars(stampToDate($row[9],$hc_cfg14)) . '</b></li>';
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
			}//end if
			
			echo '<li><a href="' . MobileRoot . '/details.php?eID=' . $row[0] . '"><b>' . cleanXMLChars(strip_tags($timeStamp)) . '</b>&nbsp;' . cleanXMLChars(strip_tags($row[1])) . '</a></li>';
		}//end while	
	} else {
		echo '<li style="padding:10px 0px 10px 5px;">' . $hc_lang_mobile['NoSearch'] . "</li>";
	}//end if	?>
	</ul>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>