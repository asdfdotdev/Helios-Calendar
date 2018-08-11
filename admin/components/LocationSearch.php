<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/locations.php');
	
	if(!isset($_POST['locName'])){
		appInstructions(0, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge1']);	?>
		
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chkFrm(){
			if(document.frmLocationSearch.locName.value == ''){
				alert('<?php echo $hc_lang_locations['Valid18'];?>');
				return false;
			} else if(document.frmLocationSearch.locName.value.length < 4) {
				alert('<?php echo $hc_lang_locations['Valid19'];?>');
				return false;
			}//end if
			return true;
		}//end chkFrm()
		//-->
		</script>
		<br />
		<form name="frmLocationSearch" id="frmLocationSearch" method="post" action="<?php echo CalAdminRoot;?>/index.php?com=locsearch" onsubmit="return chkFrm();">
		<fieldset>
			<legend><?php echo $hc_lang_locations['SearchLabel'];?></legend>
			<div class="frmReq">
				<label for="locName"><?php echo $hc_lang_locations['LocName'];?></label>
				<input type="text" name="locName" id="locName" value="" size="25" maxlength="100" />
			</div>
		</fieldset>
		<br />
		<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_locations['Search'];?>" class="button" />
		</form>
<?php
	} else {
		appInstructions(0, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge2']);
	
		$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE MATCH(Name) AGAINST('" . cIn(cleanSpecialChars($_POST['locName'])) . "' IN BOOLEAN MODE) AND IsActive = 1 ORDER BY IsPublic, Name");
		if(hasRows($result)){	?>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			function chkFrm(){
				if(validateCheckArray('frmMergeLocation','locID[]',2) == 1){
					alert('<?php echo $hc_lang_locations['Valid04'];?>');
					return false;
				}//end if
				return true;
			}//end chkFrm()
			//-->
			</script>
	<?php
			echo '<div style="text-align:right;clear:both;padding-top:10px;">';
			echo '[ <a class="main" href="javascript:;" onclick="checkAllArray(\'frmMergeLocation\', \'locID[]\');">' . $hc_lang_locations['SelectAll'] . '</a>';
			echo '&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray(\'frmMergeLocation\', \'locID[]\');">' . $hc_lang_locations['DeselectAll'] . '</a> ]';
			echo '</div>';
			
			echo '<div class="locList">';
			echo '<div class="locName">' . $hc_lang_locations['NameLabel'] . '</div>';
			echo '<div class="locStatus">' . $hc_lang_locations['StatusLabel'] . '</div>';
			echo '&nbsp;</div>';
			echo '<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="' . CalAdminRoot . '/index.php?com=location&amp;m=1" onsubmit="return chkFrm();">';
			
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				echo ($cnt % 2 == 1) ? '<div class="locNameHL">' : '<div class="locName">';
				echo $row[1] . '</div>';
				
				echo ($cnt % 2 == 1) ? '<div class="locStatusHL">' : '<div class="locStatus">';
				echo ($row[12] == 1) ? $hc_lang_locations['Public'] : $hc_lang_locations['AdminOnly'];
				echo '</div>';
				
				echo ($cnt % 2 == 1) ? '<div class="locToolsHL">' : '<div class="locTools">';
				echo '<input type="checkbox" name="locID[]" id="locID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />';
				echo '</div>';
				$cnt++;
			}//end while
			
			echo '<div style="text-align:right;clear:both;padding-top:10px;">';
			echo '[ <a class="main" href="javascript:;" onclick="checkAllArray(\'frmMergeLocation\', \'locID[]\');">' . $hc_lang_locations['SelectAll'] . '</a>';
			echo '&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray(\'frmMergeLocation\', \'locID[]\');">' . $hc_lang_locations['DeselectAll'] . '</a> ]';
			echo '</div>';
			
			echo '<input name="submit" id="submit" type="submit" value="' . $hc_lang_locations['MergeLoc'] . '" class="button" />';
			echo '</form>';
		} else {
			echo "<br />" . $hc_lang_locations['NoLoc'];
		}//end if
	}//end if?>