<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/settings.php');

	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_settings['Feed01']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Censored_Words", $hc_lang_settings['TitleCensored'], $hc_lang_settings['InstructCensored']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 55");
	$wordList = mysql_result($result,0,0);
		
	echo '<br />';
	echo '<form name="frmCensoredWords" id="frmCensoredWords" method="post" action="' . CalAdminRoot . '/components/SettingsWordsAction.php">';
	echo '<fieldset><legend>' . $hc_lang_settings['CensoredWord'] . '</legend>';
	echo '<textarea name="wordList" id="wordList" rows="25" cols="80" style="width:95%;">' . $wordList . '</textarea>';
	echo '</fieldset><br />';
	echo '<input type="submit" name="submit" id="submit" value="' . $hc_lang_settings['SaveWords'] . '" />';
	echo '</form>';?>