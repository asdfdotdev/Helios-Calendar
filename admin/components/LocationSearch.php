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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/locations.php');
	
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
			<legend>Location Merge Search</legend>
			<div class="frmReq">
				<label for="locName">Location Name:</label>
				<input type="text" name="locName" id="locName" value="" size="25" maxlength="100" />
			</div>
		</fieldset>
		<br />
		<input type="submit" name="submit" id="submit" value="Begin Search" class="button" />
		</form>
<?php
	} else {
		appInstructions(0, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge2']);
	
		$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE MATCH(Name) AGAINST('" . cIn(cleanSpecialChars($_POST['locName'])) . "' IN BOOLEAN MODE) AND IsActive = 1 ORDER BY Name");
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
			<div style="text-align:right;clear:both;padding-top:10px;">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('frmMergeLocation', 'locID[]');"><?php echo $hc_lang_locations['SelectAll'];?></a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('frmMergeLocation', 'locID[]');"><?php echo $hc_lang_locations['DeselectAll'];?></a> ]
			</div>
			<div class="locList">
				<div class="locName"><?php echo $hc_lang_locations['NameLabel'];?></div>
				<div class="locStatus"><?php echo $hc_lang_locations['StatusLabel'];?></div>
				&nbsp;
			</div>
			<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="<?php echo CalAdminRoot . "/index.php?com=location&amp;m=1";?>" onsubmit="return chkFrm();">
	<?php 	$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="locName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="locStatus<?php if($cnt % 2 == 1){echo "HL";}?>">
			<?php 	if($row[12] == 1){
						echo $hc_lang_locations['Public'];
					} else {
						echo $hc_lang_locations['AdminOnly'];
					}//end if	?>
				</div>
				<div class="locTools<?php if($cnt % 2 == 1){echo "HL";}?>">
					<input type="checkbox" name="locID[]" id="locID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />
				</div>
		<?php 	$cnt++;
			}//end while	?>
			<div style="text-align:right;clear:both;padding-top:10px;border-top: 1px solid #000000;">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('frmMergeLocation', 'locID[]');"><?php echo $hc_lang_locations['SelectAll'];?></a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('frmMergeLocation', 'locID[]');"><?php echo $hc_lang_locations['DeselectAll'];?></a> ]
			</div>
			<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_locations['MergeLoc'];?>" class="button" />
			</form>
	<?php
		} else {
			echo "<br />" . $hc_lang_locations['NoLoc'];
		}//end if
	}//end if?>