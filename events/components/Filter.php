<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$browseCatIDs = "0";
	
	if(isset($_SESSION["hc_favCat"]) || isset($_SESSION["hc_favCity"])){
		if(isset($_SESSION["hc_favCat"])){
			$browseCatIDs = $_SESSION["hc_favCat"];
		}//end if	?>
		<br />
		<b>Your favorites filter is set.</b><br />
		To update your favorites settings check, or uncheck, your preferred options below.
		<br /><br />
		Click 'Set Favorites' below to update your settings.
<?php
	} else {	?>
		<br />
		Select the criteria you wish to browse below.
		<br /><br />
		Click 'Set Favorites' below to view only events in the categories you choose.
<?php
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm() {
	dirty = 0;
	warn = "Your favorites could not be set because of the following reasons:\n";
		
		if(validateCheckArray('frmEventFilter','catID[]',1) > 0 && validateCheckArray('frmEventFilter','cityName[]',1) > 0){
			dirty = 1;
			warn = warn + '\n*Category or City Selection is Required';
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
	<form name="frmEventFilter" id="frmEventFilter" method="post" action="<?php echo CalRoot;?>/components/FilterAction.php" onSubmit="return chkFrm();">
	<fieldset>
		<legend>Select Favorites Settings</legend>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label class="category"><input name="cookieme" id="cookieme" type="checkbox" <?php if(isset($_COOKIE["hc_favCat"]) || isset($_COOKIE['hc_favCity'])){echo "checked=\"checked\"";}?> class="noBorderIE" />&nbsp;Remember&nbsp;My&nbsp;Favorites</label>
		</div>
		<br />
	<?php	$cities = getCities();
			if(count($cities) > 0){	?>
				<br /><div class="frmOpt">
				<label>Cities:</label>
				<table cellpadding="0" cellspacing="0" border="0"><tr>
		<?php	$cnt = 0;
				$favCities = array();
				if(isset($_SESSION['hc_favCity'])){$favCities = explode(',', $_SESSION['hc_favCity']);}//end if
				foreach($cities as $val){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
					if($val != ''){	
						$findMe = "'" . $val . "'";	?>
						<td><label for="cityName_<?php echo $val;?>" class="category"><input <?php if(in_array($findMe, $favCities)){echo "checked=\"checked\"";}//end if?> name="cityName[]" id="cityName_<?php echo $val;?>" type="checkbox" value="<?php echo $val;?>" class="noBorderIE" /><?php echo $val;?></label></td>
		<?php		}//end if
					$cnt++;
				}//end foreach	?>
				</tr></table>
				</div>
		<?php	
			}//end if	?>
		<br />
		<div class="frmOpt">
			<label>Categories:</label>
	<?php	$query = "SELECT a.*, b.PkID as Selected
						FROM " . HC_TblPrefix . "categories a
							LEFT JOIN " . HC_TblPrefix . "categories b ON (a.PkID = b.PkID AND b.PkID IN (" . cIn($browseCatIDs) . ")) 
						WHERE a.IsActive = 1
						ORDER BY CategoryName";
			getCategories('frmEventFilter', 2, $query);	?>
		</div>
	</fieldset>
	<br />	
	<input name="submit" id="submit" type="submit" value="Set Favorites" class="button" />&nbsp;
	<input onclick="window.location.href='<?php echo CalRoot;?>/components/FilterAction.php?clear=1'" name="clear" id="clear" type="button" value="Clear Favorites" class="button" />
	</form>