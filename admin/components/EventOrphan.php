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
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/manage.php');
	
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed02']);
				break;
		}//end switch
	}//end if
	
	$result = doQuery("SELECT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID)
					WHERE
						" . HC_TblPrefix . "events.IsActive = 1 AND
						" . HC_TblPrefix . "events.IsApproved = 1 AND
						(" . HC_TblPrefix . "eventcategories.EventID IS NULL OR
						" . HC_TblPrefix . "categories.IsActive = 0)
					ORDER BY StartDate");
	if(hasRows($result)){	
		appInstructions(0, "Orphan_Events", $hc_lang_manage['TitleOrphan'], $hc_lang_manage['InstructOrphan']);	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(eID){
			if(confirm('<?php echo $hc_lang_manage['Valid04'] . "\\n\\n          " . $hc_lang_manage['Valid05'] . "\\n          " . $hc_lang_manage['Valid06'];?>')){
				window.location.href='<?php echo CalAdminRoot . "/components/EventDelete.php";?>?dID=' + eID + '&oID=1';
			}//end if
		}//end doDelete
		
		function chkFrm(){
			if(validateCheckArray('eventOrphan','eventID[]',1) == 1){
				alert('<?php echo $hc_lang_manage['Valid07'];?>');
				return false;
			} else {
				if(confirm('<?php echo $hc_lang_manage['Valid04'] . "\\n\\n          " . $hc_lang_manage['Valid05'] . "\\n          " . $hc_lang_manage['Valid06'];?>')){
					return true;
				} else {
					return false;
				}//end if
			}//end if
			return true;
		}//end chkFrm()
		//-->
		</script>
		<form name="eventOrphan" id="eventOrphan" method="post" action="<?php echo CalAdminRoot?>/components/EventDelete.php" onsubmit="return chkFrm();">
		<input type="hidden" name="oID" id="oID" value="1" />
		<br />
		<div class="catCtrl">&nbsp;
			[ <a class="catLink" href="javascript:;" onclick="checkAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['SelectAll'];?></a>
			&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['DeselectAll'];?></a> ]
		</div>
		<div class="orphanList">
			<div class="orphanListTitle"><b><?php echo $hc_lang_manage['Title'];?></b></div>
			<div class="orphanListDate"><b><?php echo $hc_lang_manage['Date'];?></b></div>
			<div class="orphanListTools">&nbsp;</div>&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="orphanListTitleHL">' : '<div class="orphanListTitle">';
			echo cOut($row[1]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="orphanListDateHL">' : '<div class="orphanListDate">';
			echo StampToDate($row[9], $hc_cfg24) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="orphanListToolsHL">' : '<div class="orphanListTools">';
			echo '<div class="hc_align">&nbsp;<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '&amp;oID=1" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;</div>';
			echo '&nbsp;<input type="checkbox" name="eventID[]" id="eventID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />&nbsp;</div>';
	 		
			++$cnt;
		}//end while	?>
		
		<div class="catCtrl" style="padding-top:10px;">
			[ <a class="catLink" href="javascript:;" onclick="checkAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['SelectAll'];?></a>
			&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['DeselectAll'];?></a> ]
		</div>
		<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_manage['Delete'];?>" class="button" />
		</form>
<?php
	} else {
		echo '<br />';
		echo '<div style="font-weight:bold;height:250px;">' . $hc_lang_manage['NoOrphan'] . '</div>';
	}//end if	?>