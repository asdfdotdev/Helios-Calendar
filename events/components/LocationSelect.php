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
	$f = (!isset($pubOnly)) ? 'a' : '';
	if(!file_exists(realpath('../events/cache/locList'.$f.'.php'))){
		include($hc_langPath . $_SESSION['LangSet'] . '/public/select.php');

		$a = (isset($pubOnly)) ? 'AND IsPublic = 1' : '';
		$result = doQuery("SELECT PkID, Name
						FROM " . HC_TblPrefix . "locations
						WHERE IsActive = 1 " . $a . " ORDER BY Name");
		ob_start();
		$fp = fopen('../events/cache/locList'.$f.'.php', 'w');

		fwrite($fp, "<?php\n//\tHelios Location List - Delete this file when upgrading.\n\n");
		fwrite($fp, "\$NewAll = (isset(\$NewAll)) ? \$NewAll : '';?>\n\n");
		echo '<select name="locListI" id="locListI" onchange="if(isNaN(this.options[this.selectedIndex].value)){splitLocation(this.options[this.selectedIndex].value);}">';
		if(hasRows($result)){
			echo "\n\t" . '<option value="0|"><?php echo $NewAll;?></option>';
			echo "\n\t" . '<option value="-1">-------------------------</option>';
			while($row = mysql_fetch_row($result)){
				echo "\n\t" . '<option value="'.$row[0].'|'.cOut($row[1]).'">' . cOut($row[1]) . '</option>';
			}//end while
		} else {
			echo "\n\t" . '<option value="0">' . $hc_lang_select['NoLocList'] . '</option>';
		}//end if
		echo "\n" . '</select><br />';
		
		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}//end if

	if(file_exists(realpath('../events/cache/locList'.$f.'.php'))){
		include('../events/cache/locList'.$f.'.php');
	}//end if?>