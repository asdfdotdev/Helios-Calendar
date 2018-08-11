<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/settings.php');

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