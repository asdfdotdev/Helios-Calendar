<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/user.php');

	$token = set_form_token(1);
	$ban = (isset($_GET['b'])) ? 1 : 0;
	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$term = $save = $queryS = '';
	if(isset($_GET['s']) && $_GET['s'] != ''){
		$term = cIn(cleanQuotes(strip_tags($_GET['s'])));
		$save = '&s='.$term;
		$queryS = " AND (NetworkName LIKE('%".$term."%') OR Email LIKE('%".$term."%'))";
	}
	
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix  . "users WHERE IsBanned = '". cIn($ban) . "' $queryS");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	$resOffset = ($pages <= $resOffset && $pages > 0) ? $pages - 1 : $resOffset;
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_user['Feed01']);
				break;
			case "2" :
				feedback(1, $hc_lang_user['Feed02']);
				break;
			case "3" :
				feedback(1, $hc_lang_user['Feed03']);
				break;
		}
	}
	
	appInstructions(0, "Manage_Users", $hc_lang_user['TitleManage'], $hc_lang_user['InstructManage']);

	echo '
		<fieldset style="border:0px;">
			<label><b>'.$hc_lang_user['UserLabel'].'</b></label>
			<span class="output">'
			.(($ban == 0) ? '<b>'.$hc_lang_user['UserStatus0'].'</b>':'<a href="'.AdminRoot.'/index.php?com=user&a='.$resLimit.$save.'">'.$hc_lang_user['UserStatus0'].'</a>').' | '
			.(($ban == 1) ? '<b>'.$hc_lang_user['UserStatus1'].'</b>':'<a href="'.AdminRoot.'/index.php?com=user&b=1&a='.$resLimit.$save.'">'.$hc_lang_user['UserStatus1'].'</a>').'
			</span>
			<label><b>'.$hc_lang_user['ResPer'].'</b></label>
			<span class="output">';
	
	$save .= ($ban == 1) ? '&b=1':'';
	
	for($x = 25;$x <= 100;$x = $x + 25){
		echo ($x > 25) ? '&nbsp;|&nbsp;' : '';			
		echo ($x != $resLimit) ?
			'<a href="'.AdminRoot.'/index.php?com=user&amp;p='.$resOffset.'&amp;a='.$x.$save.'">'.$x.'</a>':
			'<b>'.$x.'</b>';
	}
		
	echo '
			</span>
			<label><b>'.$hc_lang_user['Page'].'</b></label>
			<span class="output">';

	$x = ($resOffset - $resDiff > 0) ? $resOffset - $resDiff : 0;
	$cnt = 0;

	echo ($resOffset > $resDiff) ? '<a href="'.AdminRoot.'/index.php?com=user&p=0&a='.$resLimit.'">1</a>&nbsp;...&nbsp;' : '';

	while($cnt <= ($resDiff * 2) && $x < $pages){
		echo ($cnt > 0) ? ' | ' : '';
		echo ($resOffset != $x) ?
			'<a href="'.AdminRoot.'/index.php?com=user&amp;p='.$x.'&amp;a='.$resLimit.$save.'">'.($x + 1).'</a>':
			'<b>' . ($x + 1) . '</b>';
		++$x;
		++$cnt;
	}

	echo ($resOffset < ($pages - ($resDiff + 1))) ? '&nbsp;...&nbsp;<a href="'.AdminRoot.'/index.php?com=user&p='.($pages - 1).'&a='.$resLimit.$save.'">'.$pages.'</a>' : '';
	echo (($cnt == 0) ? '<b>1</b>':'').'
			</span>
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<input name="filter" id="filter" type="text" size="30" maxlength="50" value="'.$term.'" />
				<input name="filter_go" id="filter_go" type="button" value="'.$hc_lang_user['FilterUsers'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=user&p=0&a='.$resLimit.$save.'&s=\'+document.getElementById(\'filter\').value;" />
			</span>
			'.(($term != '') ? '<label>&nbsp;</label><span class="output"><a href="'.AdminRoot.'/index.php?com=user&p=0&a='.$resLimit.'">'.$hc_lang_user['AllUsersLink'].'</a></span>' : '').'
		</fieldset>';

	$result = doQuery("SELECT PkID, NetworkType, NetworkName, NetworkID, Email, SignIns, LastSignIn, LastIP, Level, IsBanned,
						(SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events e WHERE e.OwnerID = u.PkID AND e.IsActive = 1) as Events
					FROM " . HC_TblPrefix  . "users u
					WHERE IsBanned = '". cIn($ban) . "' $queryS ORDER BY NetworkName LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:20%;">'.$hc_lang_user['NameLabel'].'</div>
				<div style="width:24%;">'.$hc_lang_user['EmailLabel'].'</div>
				<div style="width:7%;">'.$hc_lang_user['NetworkLabel'].'</div>
				<div style="width:12%;">'.$hc_lang_user['LastSignLabel'].'</div>
				<div style="width:10%;">'.$hc_lang_user['SignLabel'].'</div>
				<div style="width:8%;">'.$hc_lang_user['SubLabel'].'</div>
				<div style="width:19%;">&nbsp;</div>
			</li>';
		
		$cnt = 1;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 0) ? ' hl':'';
			$net_img = '';
			switch($row[1]){
				case 1:
					$net_img = 'twitter_icon';
					break;
				case 2:
					$net_img = 'facebook_icon';
					break;
				case 3:
					$net_img = 'google';
					break;
			}
			echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[3]).'" style="width:20%;">'.cOut($row[2]).'</div>
				<div class="txt" title="'.cOut($row[4]).'" style="width:25%;">'.(($row[4] != '') ? '<a href="mailto:'.cOut($row[4]).'?subject='.$hc_lang_user['EmailSubject'].'">'.cOut($row[4]).'</a>':$hc_lang_user['NoEmail']).'</div>
				<div style="width:6%;"><img src="'.AdminRoot.'/img/logos/'.$net_img.'.png" width="16" height="16" /></div>
				<div style="width:12%;">'.stampToDate($row[6], $hc_cfg[24]).'</div>
				<div class="number" style="width:9%">'.number_format($row[5], 0, '.', ',').'&nbsp;</div>
				<div class="number" style="width:9%">'.number_format($row[10], 0, '.', ',').'&nbsp;</div>
				
				<div class="tools" style="width:19%;">
					<a href="' . AdminRoot . '/index.php?com=useredit&amp;uID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/user_edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doBan(\''.$row[0].'\',\''.(($ban == 1) ? '0':'1').'\');return false;"><img src="'.AdminRoot.'/img/icons/'.(($ban == 0) ? 'user_ban':'user_unban').'.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\''.$row[0].'\',\''.(($ban == 1) ? '1':'0').'\');return false;"><img src="'.AdminRoot.'/img/icons/user_delete.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</ul>
		
		<script>
		//<!--
		function doDelete(dID,b){
			if(confirm("'.$hc_lang_user['Valid01'].'\\n\\n          '.$hc_lang_user['Valid02'].'\\n          '.$hc_lang_user['Valid03'].'"))
				document.location.href = "'.AdminRoot.'/components/UserEditAction.php?dID=" + dID + "&b=" + b + "&tkn='.$token.'";
		}
		function doBan(bID,b){
			var ban_msg = (b == 1) ? "'.$hc_lang_user['Valid04'].'\\n\\n          '.$hc_lang_user['Valid05'].'\\n          '.$hc_lang_user['Valid06'].'" :
								"'.$hc_lang_user['Valid07'].'\\n\\n          '.$hc_lang_user['Valid08'].'\\n          '.$hc_lang_user['Valid09'].'"
			if(confirm(ban_msg))
				document.location.href = "'.AdminRoot.'/components/UserEditAction.php?bID=" + bID + "&b=" + b + "&tkn='.$token.'";
		}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_user['NoUsers'] . '</p>';
	}
?>