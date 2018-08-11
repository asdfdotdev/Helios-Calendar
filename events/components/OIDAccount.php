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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/user.php');

	$uID = (isset($_SESSION['hc_OpenIDPkID'])) ? $_SESSION['hc_OpenIDPkID'] : 0;
	
	if(isset($_GET['msg']) && is_numeric($_GET['msg'])){
		switch($_GET['msg']){
			case 1:
				feedback(1,$hc_lang_user['Feed01']);
				break;
			case 2:
				feedback(2,$hc_lang_user['Feed02']);
				break;
		}//end switch
	}//end if

	$result = doQuery("SELECT oid.*, COUNT(c.PosterID) as CNT
					FROM " . HC_TblPrefix . "oidusers oid
						LEFT JOIN " . HC_TblPrefix . "comments c ON (oid.PkID = c.PosterID)
					WHERE oid.PkID = '" . cIn($uID) . "' AND oid.IsActive = 1
					GROUP BY c.PosterID");
	if(hasRows($result)){
		echo '<p>' . $hc_lang_user['AccountNotice'] . '</p>';
		echo '<form name="" id="" method="post" action="' . CalRoot . '/components/OIDAccountAction.php">';
		echo '<div class="oidUserCom">' . $hc_lang_user['AccountLabel'] . '</div><br />';
		echo '<fieldset>';
		echo '<legend>' . $hc_lang_user['DetailsLabel'] . '</legend>';
		echo '<div class="frmOpt"><label>' . $hc_lang_user['DisplayName'] . '</label>';
		echo '<input type="text" name="oidName" id="oidName" value="' . mysql_result($result,0,2) . '" size="35" maxlength="100" />';
		echo '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_user['IdentURL'] . '</label>';
		echo '<a href="' . $_SESSION['hc_OpenID'] . '" class="eventMain" target="_blank">' . $_SESSION['hc_OpenID'] . '</a>';
		echo '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_user['FirstLogin'] . '</label>';
		echo mysql_result($result,0,4);
		echo '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_user['LastLogin'] . '</label>';
		echo mysql_result($result,0,5);
		echo '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_user['Comments'] . '</label>';
		echo mysql_result($result,0,8);
		echo '</div>';
		echo '</fieldset>';
		echo '<br /><input type="submit" name="submit" id="submit" value="' . $hc_lang_user['SaveAccount'] . '" />';
		echo '</form>';
	} else {
		echo $hc_lang_user['NoUser'];
	}//end if
	echo '<br /><br /><br /><br /><br />'?>