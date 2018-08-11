<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/reports.php');
	appInstructions(0, "Reports#Duplicate_Event_Report", $hc_lang_reports['TitleDup'], $hc_lang_reports['InstructDup']);
	echo '<br />';

	$compTitle = (!isset($_GET['t'])) ? 'e1.Title = e2.Title AND ' : '';

	$result = doQuery("SELECT e1.PkID as `First`, e1.Title as Title1, e1.StartDate as Date1, e1.LocID as LocID1, e1.LocationName, l.Name,
						e2.PkID as `Duplicate`, e2.Title as Title2, e2.StartDate as Date2, e2.LocID as LocID2, e2.LocationName, l.Name
					FROM " . HC_TblPrefix . "events e1
					    LEFT JOIN " . HC_TblPrefix . "events e2 ON (" . $compTitle . "e1.StartDate = e2.StartDate AND e1.StartTime = e2.StartTime AND e1.LocID = e2.LocID)
					    LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = e1.LocID OR l.PkID = e2.LocID)
					WHERE e1.PkID != e2.PkID AND e1.IsActive = 1 AND e2.IsActive = 1
					GROUP BY e2.PkID
					ORDER BY e1.PkID");
?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_reports['Valid01'];?>\n\n          <?php echo $hc_lang_reports['Valid02'];?>\n          <?php echo $hc_lang_reports['Valid03'];?>')){
			window.location.href='<?php echo CalAdminRoot;?>/components/EventDelete.php?dID=' + dID + '&dpID=1';
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_reports['Feed01']);
				break;
		}//end switch
	}//end if

	if(hasRows($result)){
		echo '<input type="checkbox" onclick="window.location.href=\'' . CalAdminRoot . '/index.php?com=reportdup';
		echo (!isset($_GET['t'])) ? '&t=1\'" checked="checked" />' : '\'" />';
		echo $hc_lang_reports['IncludeTitle'];

		$cnt = $curID = 0;
		$foundDup = array();
		while($row = mysql_fetch_row($result)){
			if($curID != $row[0] && !in_array($row[0],$foundDup)){
				$cnt = 0;
				$curID = $row[0];
				echo '<div class="duplicateList" style="line-height:20px;">';
				echo '<div class="duplicateTitle">' . $row[1] . ' (' . $row[0] . ')</div>';
				echo '<div class="duplicateDate">' . stampToDate($row[2], $hc_cfg24) . '</div>';
				echo '<div class="duplicateTools">';
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="javascript:;" onclick="doDelete(' . $row[0] . ')" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo '</div>&nbsp;</div>';
			}//end if

			if(!in_array($row[0],$foundDup)){
				$foundDup[] = $row[6];
				echo ($cnt % 2 == 0) ? '<div class="duplicateTitle">' : '<div class="duplicateTitleHL">';
				echo $row[7] . ' (' . $row[6] . ')</div>';
				
				echo ($cnt % 2 == 0) ? '<div class="duplicateDate">' : '<div class="duplicateDateHL">';
				echo stampToDate($row[8], $hc_cfg24) . '</div>';
				
				echo ($cnt % 2 == 0) ? '<div class="duplicateTools">' : '<div class="duplicateToolsHL">';
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[6] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="javascript:;" onclick="doDelete(' . $row[6] . ')" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo '</div>';
			}//end if
			++$cnt;
		}//end while
	} else {
		echo $hc_lang_reports['NoEvent'];
	}//end if	?>