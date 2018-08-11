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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Event_Export", $hc_lang_tools['TitleExport'], $hc_lang_tools['InstructExport']);?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_tools['Valid01'];?>";
	startDate = document.frmEventExport.startDate.value;
	endDate = document.frmEventExport.endDate.value;
		
		if(!isDate(document.frmEventExport.startDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid02'] . " " . strtolower($hc_popDateFormat);?>';
		} else if(document.frmEventExport.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_tools['Valid03'];?>";
		}//end if 
		
		if(!isDate(document.frmEventExport.endDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid04'] . " " . strtolower($hc_popDateFormat);?>';
		} else if(document.frmEventExport.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_tools['Valid05'];?>";
		}//end if
		
		if(compareDates(startDate, '<?php echo $hc_popDateValid;?>', endDate, '<?php echo $hc_popDateValid;?>') == 1){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_tools['Valid06'];?>";
		}//end if
	
		if(validateCheckArray('frmEventExport','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid07'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_tools['Valid08'];?>');
			return false;
		} else {
			if(document.frmEventExport.mID.value == 1){
				document.frmEventExport.target='_blank';
			} else if(document.frmEventExport.mID.value == 2) {
				document.frmEventExport.target='_self';
			}//end if
			return true;
		}//end if
	}//end chkFrm
	
	var calx = new CalendarPopup("dsCal");
	document.write(calx.getStyles());
	//-->
	</script>
	<form name="frmEventExport" id="frmEventExport" method="post" action="<?php echo CalAdminRoot . "/components/ToolExportAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo $hc_popDateFormat;?>" />
	<input type="hidden" name="timeFormat" id="timeFormat" value="<?php echo $hc_timeFormat;?>" />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Export'];?></legend>
		<div class="frmReq">
			<select name="eID" id="eID">
				<option value="1"><?php echo $hc_lang_tools['Export1'];?></option>
				<option value="2"><?php echo $hc_lang_tools['Export2'];?></option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Delivery'];?></legend>
		<div class="frmReq">
			<select name="mID" id="mID">
				<option value="1"><?php echo $hc_lang_tools['Delivery1'];?></option>
				<option value="2"><?php echo $hc_lang_tools['Delivery2'];?></option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Range'];?></legend>
		<div class="frmReq">
			<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?php echo strftime($hc_popDateFormat);?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventExport.startDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>&nbsp;
			&nbsp;<?php echo $hc_lang_tools['To'];?>&nbsp;&nbsp;
			<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?php echo strftime($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventExport.endDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['CategoriesLabel'];?></legend>
		<div class="frmReg">
	<?php 	$query = "SELECT " . HC_TblPrefix . "categories.*, NULL as EventID FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName";	?>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
		<?php 	$result = doQuery($query);
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?php echo $row[0];?>" class="category"><input <?php if($row[6] != ''){echo "checked=\"checked\"";}//end if?> name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
			<?php 	$cnt = $cnt + 1;
				}//end while?>
			</tr></table>
	<?php 	if($cnt > 1){	?>
				<div style="text-align:right;padding:10px 0px 10px 0px;">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('frmEventExport', 'catID[]');"><?php echo $hc_lang_tools['SelectAll'];?></a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('frmEventExport', 'catID[]');"><?php echo $hc_lang_tools['DeselectAll'];?></a> ]
				</div>
	<?php 	}//end if	?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_tools['Generate'];?> " class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>