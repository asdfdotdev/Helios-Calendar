<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/admin.php');	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_admin['Valid07'] . "\\n\\n          " . $hc_lang_admin['Valid08'] . "\\n          " . $hc_lang_admin['Valid09'];?>')){
			document.location.href = '<?php echo CalAdminRoot . "/components/AdminEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_admin['Feed03']);
				break;
			case "2" :
				feedback(3, $hc_lang_admin['Feed04']);
				break;
			case "3" :
				feedback(1, $hc_lang_admin['Feed05']);
				break;
			case "4" :
				feedback(1, $hc_lang_admin['Feed06']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Admin_Users", $hc_lang_admin['TitleBrowseA'], $hc_lang_admin['InstructBrowseA']);
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "admin WHERE SuperAdmin = 0 AND IsActive = 1 ORDER BY LastName, FirstName");
	if(hasRows($result)){
		echo '<div class="adminList">';
		echo '<div class="adminName">' . $hc_lang_admin['Name'] . '</div>';
		echo '<div class="adminEmail">' . $hc_lang_admin['EmailLabel'] . '</div>';
		echo '&nbsp;</div>';
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="adminNameHL">' : '<div class="adminName">';
			echo $row[1] . ' ' . $row[2] . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="adminEmailHL">' : '<div class="adminEmail">';
			echo $row[3] . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="adminToolsHL">' : '<div class="adminTools">';
			echo '<a href="' . CalAdminRoot . '/index.php?com=adminedit&amp;uID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
			
			$cnt++;
		}//end while
	} else {
		echo '<br />' . $hc_lang_admin['NoAdmin'];
	}//end if	?>