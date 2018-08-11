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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/settings.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,7) ORDER BY PkID");
	$keywords = cOut(mysql_result($result,0,0));
	$description = cOut(mysql_result($result,1,0));
	$allowindex = cOut(mysql_result($result,2,0));	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		var dirty = 0;
		var warn = '<?php echo $hc_lang_settings['Valid23'];?>\n';
		
		if(document.frm.keywords.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid24'];?>';
		}//end if
		
		if(document.frm.description.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid25'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_settings['Valid26'];?>');
			return false;
		}//end if
		
	}//end if
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_settings['Feed02']);
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Search_Optimization", $hc_lang_settings['TitleSearch'], $hc_lang_settings['InstructSearch']);	?>
	<br />
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/SearchOptimizeAction.php";?>" onsubmit="return chkFrm();">
	<fieldset>
		<legend><?php echo $hc_lang_settings['MetaSettings'];?></legend>
		<div class="frmOpt">
			<label for="indexing"><?php echo $hc_lang_settings['Indexing'];?></label>
			<select name="indexing" id="indexing">
				<option <?php if($allowindex == 1){echo "selected=\"selected\"";}//end if?> value="1"><?php echo $hc_lang_settings['Indexing1'];?></option>
				<option <?php if($allowindex == 0){echo "selected=\"selected\"";}//end if?> value="0"><?php echo $hc_lang_settings['Indexing0'];?></option>
			</select>
			(<i><?php echo $hc_lang_settings['Ignored'];?></i>)
		</div>
		<div class="frmOpt">
			<label for="keywords"><?php echo $hc_lang_settings['Keywords'];?></label>
			<input name="keywords" id="keywords" size="60" maxlength="250" type="text" value="<?php echo $keywords;?>" />
		</div>
		<div class="frmOpt">
			<label for="description"><?php echo $hc_lang_settings['Description'];?></label>
			<input name="description" id="description" size="60" maxlength="250" type="text" value="<?php echo $description;?>" />
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_settings['SaveMeta'];?> " class="button" />
	</form>