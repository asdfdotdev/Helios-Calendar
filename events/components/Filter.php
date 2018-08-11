<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if( isset($_SESSION["hc_favorites"]) ){
		$browseCatIDs = $_SESSION["hc_favorites"];	?>
		<br />
		To update your favorites check, or uncheck, the categories below.
		<br /><br />
		Click 'Set Favorites' below to update your favorites.
<?	} else {
		$browseCatIDs = "0";	?>
		<br />
		Select the categories you wish to browse from the category list.
		<br /><br />
		Click 'Set Favorites' below to view only events in the categories you choose.
<?	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm() {
	dirty = 0;
	warn = "Your favorites could not be set because of the following reasons:\n";
		
		if(validateCheckArray('frmEventFilter','catID[]',1,'Category') > 0){
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
	//-->
	</script>
	<br />
	Click 'Clear Favorites' to browse all available events.
	<br /><br />
	<form name="frmEventFilter" id="frmEventFilter" method="post" action="<?echo CalRoot;?>/components/FilterAction.php" onSubmit="return chkFrm();">
	<fieldset>
		<legend>Select Categories to Browse</legend>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label class="category"><input name="cookieme" id="cookieme" type="checkbox" <?if(isset($_COOKIE["hc_favorites"])){echo "CHECKED";}?> class="noBorderIE" />&nbsp;Remember&nbsp;My&nbsp;Favorites</label>
		</div>
		<br /><br />
		<div class="frmReq">
			<label>Categories:</label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
			<?	$result = doQuery("	SELECT a.*, b.PkID as Selected
									FROM " . HC_TblPrefix . "categories a
										LEFT JOIN " . HC_TblPrefix . "categories b ON (a.PkID = b.PkID AND b.PkID IN (" . cIn($browseCatIDs) . ")) 
									WHERE a.IsActive = 1
									ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?echo $row[0];?>" class="category"><input name="catID[]" id="catID_<?echo $row[0];?>" type="checkbox" value="<?echo $row[0];?>" <?if($row[6] != ''){echo "checked=\"checked\"";}//end if?> class="noBorderIE" /><?echo cOut($row[1]);?></label></td>
				<?	$cnt = $cnt + 1;
				}//end while?>
			</tr></table>
		<?	if($cnt > 1){	?>
			<br />
			<label>&nbsp;</label>
			[ <a class="eventMain" href="javascript:;" onclick="checkAllArray('frmEventFilter', 'catID[]');">Select All Categories</a> 
			&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray('frmEventFilter', 'catID[]');">Deselect All Categories</a> ]
		<?	}//end if	?>
		</div>
		<br />
		<div class="frmSubmit">
			<label>&nbsp;</label>
			<input name="submit" id="submit" type="submit" value="Set Favorites" />&nbsp;
			<input onclick="window.location.href='<?echo CalRoot;?>/components/FilterAction.php?clear=1'" name="clear" id="clear" type="button" value="Clear Favorites" />
		</div>
	</fieldset>
	</form>