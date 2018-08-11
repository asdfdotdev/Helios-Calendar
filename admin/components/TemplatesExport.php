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
				feedback(1, $hc_lang_tools['Feed05']);
				break;
			case "2" :
				feedback(1, $hc_lang_tools['Feed04']);
				break;
			case "3" :
				feedback(1, $hc_lang_tools['Feed06']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Export_Templates", $hc_lang_tools['TitleExpTemp'], $hc_lang_tools['InstructExpTemp']);
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "templates WHERE TypeID = 1 AND IsActive = 1 ORDER BY Name");
	if(hasRows($result)){?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_tools['Valid16'] . "\\n\\n          " . $hc_lang_tools['Valid17'] . "\\n          " . $hc_lang_tools['Valid18'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/TemplatesEditAction.php";?>?dID=' + dID;
			}//end if
		}//end doDelete
		//-->
		</script>
<?php
		echo '<br />';
		echo '<a href="' . CalAdminRoot . '/index.php?com=templateedit" class="icon"><img src="' . CalAdminRoot . '/images/icons/iconAdd.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" />&nbsp;' . $hc_lang_tools['AddT'] . '</a><br />';
		
		echo '<div class="tempExpList">';
		echo '<div class="tempExpName">' . $hc_lang_tools['NameLabel'] . '</div>';
		echo '<div class="tempExpStatus">' . $hc_lang_tools['GroupLabel'] . '</div>';
		echo '<div class="tempExpStatus">' . $hc_lang_tools['SortLabel'] . '</div>';
		echo '&nbsp;</div>';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="tempExpNameHL">' : '<div class="tempExpName">';
			echo $row[1] . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="tempExpStatusHL">' : '<div class="tempExpStatus">';
			switch($row[7]){
				case 0:
					echo $hc_lang_tools['OptCategory'];
					break;
				case 1:
					echo $hc_lang_tools['OptEvent'];
					break;
				case 2:
					echo $hc_lang_tools['OptEventS'];
					break;
				case 3:
					echo $hc_lang_tools['OptEventSC'];
					break;
			}//end switch
			echo '</div>';
						
			echo ($cnt % 2 == 1) ? '<div class="tempExpStatusHL">' : '<div class="tempExpStatus">';
			echo $hc_lang_tools['OptSort' . $row[8]] . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="tempExpToolsHL">' : '<div class="tempExpTools">';
			echo '<a href="' . CalAdminRoot . '/index.php?com=templateedit&amp;tID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '</div>';
			++$cnt;
		}//end while
	} else {
		echo "<br />" . $hc_lang_tools['NoTemps'];
	}//end if	?>