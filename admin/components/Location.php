<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/locations.php');
	
	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;

	$hc_Side[] = array(CalRoot . '/index.php?com=location','iconMap.png',$hc_lang_locations['LinkMap'],1);
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "3" :
				feedback(1, $hc_lang_locations['Feed03']);
				break;
			case "4" :
				feedback(2, $hc_lang_locations['Feed04']);
				break;
			case "5" :
				feedback(2, $hc_lang_locations['Feed05']);
				break;
			case "6" :
				feedback(1, $hc_lang_locations['Feed06']);
				break;
			case "7" :
				feedback(2, $hc_lang_locations['Feed07']);
				break;
			case "8" :
				feedback(1, $hc_lang_locations['Feed08']);
				break;
			case "9" :
				feedback(1, $hc_lang_locations['Feed09']);
				break;
			case "10" :
				feedback(2, $hc_lang_locations['Feed10']);
				break;
			case "11" :
				feedback(2, $hc_lang_locations['Feed11']);
				break;
			case "12" :
				feedback(1, $hc_lang_locations['Feed12']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Locations", $hc_lang_locations['TitleBrowse'], $hc_lang_locations['InstructBrowse']);
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($pages <= $resOffset && $pages > 0){$resOffset = ($pages - 1);}
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 ORDER BY IsPublic, Name LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo '<div style="clear:both;padding-top:10px;"><b>' . $hc_lang_locations['ResPer'] . '</b>&nbsp;';
		for($x = 25;$x <= 100;$x = $x + 25){
			if($x > 25){echo "&nbsp;|&nbsp;";}			
			echo ($x != $resLimit) ?
				'<a href="' . CalAdminRoot . '/index.php?com=location&p=' . $resOffset . '&a=' . $x . '" class="eventMain">' . $x . '</a>':
				'<b>' . $x . '</b>';
		}//end for
		
		echo '</div><div style="clear:both;padding-top:10px;"><b>' . $hc_lang_locations['Page'] . '</b>&nbsp;';
		
		$x = $resOffset - $resDiff;
		$cnt = 0;
		
		if($x < 0){$x = 0;}
		if($resOffset > $resDiff){
			echo '<a href="' . CalAdminRoot . '/index.php?com=location&p=0&a=' . $resLimit . '" class="eventMain">1</a>&nbsp;...&nbsp;';
		}//end if
		
		while($cnt <= ($resDiff * 2) && $x < $pages){
			if($cnt > 0){echo " | ";}
			echo ($resOffset != $x) ?
				'<a href="' . CalAdminRoot . '/index.php?com=location&p=' . $x . '&a=' . $resLimit . '" class="eventMain">' . ($x + 1) . '</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}//end while
		
		if($resOffset < ($pages - ($resDiff + 1))){
			echo '&nbsp;...&nbsp;<a href="' . CalAdminRoot . '/index.php?com=location&p=' . ($pages - 1) . '&a=' . $resLimit . '" class="eventMain">' . $pages . '</a>';
		}//end if
		
		echo '</div><br />';	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_locations['Valid01'] . "\\n\\n          " . $hc_lang_locations['Valid02'] . "\\n          " . $hc_lang_locations['Valid03'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/LocationEditAction.php";?>?dID=' + dID;
			}//end if
		}//end doDelete
		//-->
		</script>
<?php
		echo '<div class="locList">';
		echo '<div class="locName">' . $hc_lang_locations['NameLabel'] . '</div>';
		echo '<div class="locStatus">' . $hc_lang_locations['StatusLabel'] . '</div>';
		echo '&nbsp;</div>';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="locNameHL">' : '<div class="locName">';
			echo cOut($row[1]) . '&nbsp;</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="locStatusHL">' : '<div class="locStatus">';
			echo ($row[12] == 1) ? $hc_lang_locations['Public'] : $hc_lang_locations['AdminOnly'];
			echo '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="locToolsHL">' : '<div class="locTools">';
			
			echo '<a href="' . CalAdminRoot . '/index.php?com=addlocation&amp;lID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '</div>';
			++$cnt;
		}//end while
	} else {
		echo "<br />" . $hc_lang_locations['NoLoc'];
	}//end if	?>