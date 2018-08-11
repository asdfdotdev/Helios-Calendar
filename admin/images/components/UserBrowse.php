<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/newsletter.php');	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_news['Valid08'] . "\\n\\n          " . $hc_lang_news['Valid09'] . "\\n          " . $hc_lang_news['Valid10'];?>')){
			document.location.href = '<?php echo CalAdminRoot . "/components/UserEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed05']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Recipients", $hc_lang_news['TitleBrowseR'], $hc_lang_news['InstructBrowseR']);
	
	if(isset($_POST['search'])){
		$query = "	SELECT *
				FROM " . HC_TblPrefix . "users
				WHERE IsRegistered = 1";
		
		if(isset($_POST['name'])){
			$query = $query . " AND LastName LIKE('%" . cIn($_POST['name']) . "%')";
		}//end if
		
		if(isset($_POST['email'])){
			$query = $query . " AND Email LIKE('%" . cIn($_POST['email']) . "%')";
		}//end if
		
		$query = $query . " ORDER BY LastName, FirstName";
	} else {
		$query = "SELECT * FROM " . HC_TblPrefix  . "users WHERE IsRegistered = 1 ORDER BY LastName, FirstName";
	}//end if
	
	$result = doQuery($query);
	if(hasRows($result)){
		echo '<div class="userList">';
		echo '<div class="userListName"><b>' . $hc_lang_news['Name'] . '</b></div>';
		echo '<div class="userListEmail"><b>' . $hc_lang_news['Emailb'] . '</b></div>';
		echo '<div class="userListTools">&nbsp;</div>';
		echo '&nbsp;</div>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			if($cnt >0){echo "<br />";}
			
			echo ($cnt % 2 == 1) ? '<div style="clear:both;" class="userListNameHL">' : '<div style="clear:both;" class="userListName">';
			echo cOut($row[1]) . ' ' . cOut($row[2]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="userListEmailHL">' : '<div class="userListEmail">';
			echo '&nbsp;' . cOut($row[3]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="userListToolsHL">' : '<div class="userListTools">';
			echo '<a href="' . CalAdminRoot . '/?com=useredit&amp;uID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="javascript:doDelete(\'' . $row[0] . '\'); return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
			
			++$cnt;
		}//end while
	} else {
		echo '<br />' . $hc_lang_news['NoRecip'];
	}//end if	?>