<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/tools.php');
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_tools['Feed02']);
				break;
			case "2" :
				feedback(2, $hc_lang_tools['Feed01']);
				break;
		}//end switch
	}//end if
	appInstructions(0, "Event_Import", $hc_lang_tools['TitleImport'], $hc_lang_tools['InstructImport']);?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_tools['Valid09'];?>";

		if(document.frmEventImport.eventIn.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid10'];?>';
		}//end if
		
		if(validateCheckArray('frmEventImport','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid11'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_tools['Valid12'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	
	function toggleMe(who){
		who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
		return false;
	}//end toggleMe()
	//-->
	</script>
	<form name="frmEventImport" id="frmEventImport" method="post" action="<?php echo CalAdminRoot . "/components/ToolImportAction.php";?>" onsubmit="return chkFrm();">
	<fieldset>
		<legend><?php echo $hc_lang_tools['ImportLabel'];?></legend>
		<div class="frmOpt">
			<label for="impType"><?php echo $hc_lang_tools['Import'];?></label>
			<select name="impType" id="impType" onchange="toggleMe(document.getElementById('csv'));">
				<option value="0"><?php echo $hc_lang_tools['Import0'];?></option>
				<option value="1"><?php echo $hc_lang_tools['Import1'];?></option>
			</select>
		</div>
	</fieldset>
	<br />
	<div id="csv">
		<fieldset>
			<legend><?php echo $hc_lang_tools['DataLabel'];?></legend>
			<div class="frmOpt">
				<label for="enclChar"><?php echo $hc_lang_tools['Enclosed'];?></label>
				<select name="enclChar" id="enclChar">
					<option value="2">"</option>
					<option value="1">'</option>
					<option value="0">NONE</option>
				</select>
			</div>
			<div class="frmOpt">
				<label for="termChar"><?php echo $hc_lang_tools['Terminated'];?></label>
				<select name="termChar" id="termChar">
					<option value=",">,</option>
					<option value="tab">tab</option>
				</select>
			</div>
		</fieldset>
	<br />
	</div>
	<fieldset>
		<legend><?php echo $hc_lang_tools['EventData'];?></legend>
		<div class="frmOpt">
			<label for="eventIn"><?php echo $hc_lang_tools['Data'];?></label>
			<textarea name="eventIn" id="eventIn" style="width:75%; height:200px;" rows="15" cols="55"></textarea>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_tools['Categories'];?></label>
	<?php	getCategories('frmEventImport', 3);?>
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_tools['ImportButton'];?> " class="button" />
	</form>