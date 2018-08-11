<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/settings.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (7) ORDER BY PkID");
	$allowindex = cOut(mysql_result($result,0,0));	?>
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
	
	appInstructions(0, "Search_Optimization", $hc_lang_settings['TitleSearch'], $hc_lang_settings['InstructSearch']);

	echo '<br />';
	echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/SearchOptimizeAction.php" onsubmit="return chkFrm();">';
	$cnt = 1;

	echo '<fieldset style="border:0;">';
	echo '<div class="frmOpt">';
	echo '<label for="indexing">' . $hc_lang_settings['Indexing'] . '</label>';
	echo '<select name="indexing" id="indexing">';
	echo ($allowindex == 1) ? '<option selected="selected" value="1">' : '<option value="1">';
	echo $hc_lang_settings['Indexing1'] . '</option>';
	echo ($allowindex == 0) ? '<option selected="selected" value="0">' : '<option value="0">';
	echo $hc_lang_settings['Indexing0'] . '</option>';
	echo '</select>';
	echo '&nbsp;(<i>' . $hc_lang_settings['Ignored'] . '</i>)';
	echo '</div></fieldset>';

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settingsmeta");
	while($row = mysql_fetch_row($result)){
		echo '<fieldset>';
		echo '<legend>' . $hc_lang_settings['Page' . $row[0]] . '</legend>';

		echo '<div class="frmOpt">';
		echo '<label for="pgtitle' . $row[0] . '">' . $hc_lang_settings['Title'] . '</label>';
		echo '<input name="titles[]" id="pgtitle' . $row[0] . '" size="65" maxlength="65" type="text" value="' . $row[3] . '" />';
		echo '</div>';
		echo '<div class="frmOpt">';
		echo '<label for="keywords' . $row[0] . '">' . $hc_lang_settings['Keywords'] . '</label>';
		echo '<input name="keywords[]" id="keywords' . $row[0] . '" size="65" maxlength="75" type="text" value="' . $row[1] . '" />';
		echo '</div>';
		echo '<div class="frmOpt">';
		echo '<label for="description' . $row[0] . '">' . $hc_lang_settings['Description'] . '</label>';
		echo '<input name="descriptions[]" id="description' . $row[0] . '" size="80" maxlength="160" type="text" value="' . $row[2] . '" />';
		echo '</div>';
		echo '</fieldset><br />';
		++$cnt;
	}//end while?>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_settings['SaveMeta'];?> " class="button" />
	</form>