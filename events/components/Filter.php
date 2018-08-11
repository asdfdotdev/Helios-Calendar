<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if( isset($_SESSION["hc_favorites"]) ){
		$browseCatIDs = $_SESSION["hc_favorites"];
	?>
	To update your favorites check, or uncheck, the categories below.
	<br><br>
	Click 'Set Favorites' below to update your favorites.
<?	} else {
		$browseCatIDs = "0";
	?>
	Select the categories you wish to browse from the category list.
	<br><br>
	Click 'Set Favorites' below to view only events in the categories you choose.
	<?
	}//end if
?>
<script language="JavaScript">
function chkFrm()
{
dirty = 0;
warn = "Your favorites could not be set because of the following reasons:\n";
	
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
	<br>
	Click 'Clear Favorites' to browse all available events.
	<br><br>
	<table cellpadding="0" cellspacing="0" border="0">
		<form name="eventFilter" id="eventFilter" method="post" action="<?echo HC_FilterAction;?>" onSubmit="return chkFrm();">
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="main"><input type="checkbox" name="cookieme" id="cookieme" <?if(isset($_COOKIE["hc_favorites"])){echo "CHECKED";}?>></td>
						<td class="main"><label for="cookieme">Remember my Favorites</label></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td>
		</tr>
		<tr>
			<td valign="top" class="eventMain" width="80">Categories:</td>
			<td class="eventMain">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
				<?php
					$query = "	SELECT a.*, b.PkID as Selected
								FROM " . HC_TblPrefix . "categories a
									LEFT JOIN " . HC_TblPrefix . "categories b ON (a.PkID = b.PkID AND b.PkID IN (" . cIn($browseCatIDs) . ")) 
								WHERE a.IsActive = 1
								ORDER BY CategoryName";
					$result = doQuery($query);
					$cnt = 0;
					$curCat = "";
					while($row = mysql_fetch_row($result)){
						if($row[3] > 0){
							if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
						?>
							<td class="eventMain"><input <?if($row[6] != ''){echo "checked";}//end if?> type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
							<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo cOut($row[1]);?></label>&nbsp;&nbsp;</td>
						<?
							$cnt = $cnt + 1;
						}//end if
					
					}//end while
				?>
					</tr>
				</table>
				<?	if($cnt > 1){	?>
					<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
					[ <a class="eventMain" href="javascript:;" onClick="checkAllArray('eventFilter', 'catID[]');">Select All Categories</a> 
					&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onClick="uncheckAllArray('eventFilter', 'catID[]');">Deselect All Categories</a> ]
					<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
				<?	}//end if	?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<br>
				<input type="submit" name="submit" value="Set Favorites" class="eventButton">&nbsp;
				<input onClick="window.location.href='<?echo CalRoot;?>/components/FilterAction.php?clear=1'" type="button" name="clear" value="Clear Favorites" class="eventButton">
			</td>
		</tr>
		</form>
	</table>