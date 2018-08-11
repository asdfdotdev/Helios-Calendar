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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/manage.php');	?>
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
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed02']);
				break;
		}//end switch
	}//end if
	
	$result = doQuery("	SELECT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID)
					WHERE 
						" . HC_TblPrefix . "events.IsActive = 1 AND
						" . HC_TblPrefix . "events.IsApproved = 1 AND
						" . HC_TblPrefix . "events.StartDate >= '" . date("Y-m-d") . "' AND
						(" . HC_TblPrefix . "eventcategories.EventID IS NULL OR
						" . HC_TblPrefix . "categories.IsActive = 0)
					ORDER BY StartDate");
	
	if(hasRows($result)){	
		appInstructions(0, "Orphan_Events", $hc_lang_manage['TitleOrphan'], $hc_lang_manage['InstructOrphan']);	?>
		<form name="eventOrphan" id="eventOrphan" method="post" action="<?php echo CalAdminRoot?>/components/EventDelete.php" onsubmit="return chkFrm();">
		<input type="hidden" name="oID" id="oID" value="1" />
		<br />
		<div style="text-align:right;clear:both;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['SelectAll'];?></a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['DeselectAll'];?></a> ]
		</div>
		<div class="orphanList">
			<div class="orphanListTitle"><b><?php echo $hc_lang_manage['Title'];?></b></div>
			<div class="orphanListDate"><b><?php echo $hc_lang_manage['Date'];?></b></div>
			<div class="orphanListTools">&nbsp;</div>&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="orphanListTitle<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>&amp;oID=1" class="main"><?php echo cOut($row[1]);?></a></div>
			<div class="orphanListDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo StampToDate($row[9], $hc_popDateFormat);?></div>
			<div class="orphanListTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>&amp;oID=1" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>
			<input type="checkbox" name="eventID[]" id="eventID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />&nbsp;</div>
	<?php 	$cnt++;
		}//end while	?>
		<br />&nbsp;&nbsp;<br />
		<div style="text-align:right;clear:both;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['SelectAll'];?></a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventOrphan', 'eventID[]');"><?php echo $hc_lang_manage['DeselectAll'];?></a> ]
		</div>
		<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_manage['Delete'];?>" class="button" />
		</form>
<?php
	} else {	?>
		<br />
		<b>There are currently no orphan events.</b>
<?php
	}//end if	?>