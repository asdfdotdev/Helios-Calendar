<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if( isset($_SESSION['BrowseCatIDs']) ){
		$browseCatIDs = $_SESSION['BrowseCatIDs'];
	} else {
		$browseCatIDs = "0";
	?>
	<b>Filter Not Set.</b> You may return to the calendar to browse all events. Or set the filter below.<br><br>
	<?
	}//end if
?>
<script language="JavaScript">
function chkFrm()
{
dirty = 0;
warn = "Your could not be set because of the following reasons:\n";
	
	if(validateCheckArray('eventFilter','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease make the required changes and try your filter again.');
		return false;
	} else {
		return true;
	}//end if
	
}//end chkFrm
</script>
	Select the categories you wish to browse from the category list.
	<br>
	Click 'Browse Selected Categories' below to view only events in the categories you select.
	<br><br>
	You can change the filters settings anytime by clicking the filter icon. <img src="<?echo CalRoot;?>/images/nav_filter.gif" width="20" height="19" alt="" border="0">
	<br><br><br>
	<table cellpadding="0" cellspacing="0" border="0">
		<form name="eventFilter" id="eventFilter" method="post" action="<?echo HC_FilterAction;?>" onSubmit="return chkFrm();">
		<tr>
			<td valign="top" class="eventMain" width="80">Categories:</td>
			<td class="eventMain">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
				<?php
					$query = "	SELECT " . HC_TblPrefix . "categories.*, 1 as Checked
								FROM " . HC_TblPrefix . "categories
								WHERE IsActive = 1
									AND PkID IN (" . $browseCatIDs . ")
								UNION
								SELECT " . HC_TblPrefix . "categories.*, 0 as Checked
								FROM " . HC_TblPrefix . "categories
								WHERE IsActive = 1
									AND PkID NOT IN (" . $browseCatIDs . ")
								ORDER BY CategoryName";
						//echo $query;exit;
					
					$result = doQuery($query);
					$cnt = 0;
					$curCat = "";
					while($row = mysql_fetch_row($result)){
						if($row[3] > 0){
							if((fmod($cnt,3) == 0) AND ($cnt > 0)){echo "</tr><tr>";}//end if
							
							if($curCat != $row[1]){
								$curCat = $row[1];
						?>
							<td class="eventMain"><input <?if($row[6] == 1){echo "checked";}//end if?> type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
							<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
						<?
							$cnt = $cnt + 1;
							}//end if
						}//end if
					
					}//end while
				?>
					</tr>
				</table>
				<?	if($cnt > 1){	?>
					<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
					[ <a class="eventMain" href="javascript:;" onClick="checkAllArray('eventFilter', 'catID[]');">Select All Categories</a> 
					&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onClick="uncheckAllArray('eventFilter', 'catID[]');">Deselect All Categories</a> ]
					<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
				<?	}//end if	?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<br>
				<input type="submit" name="submit" value="Browse Selected Categories" class="eventButton">
			</td>
		</tr>
		</form>
	</table>