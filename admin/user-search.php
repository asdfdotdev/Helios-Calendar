<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('loader.php');
	include(HCLANG.'/admin/user.php');
	
	admin_logged_in();
	action_headers();
	
	header('content-type: text/html; charset=' . $hc_lang_config['CharSet']);
	
	$resLimit = 10;
	$q = (isset($_GET['q']) && $_GET['q'] !='') ? cIn(strip_tags($_GET['q'])) : '';
	$resOffset = (isset($_GET['o']) && is_numeric($_GET['o'])) ? cIn(strip_tags($_GET['o'])) : 0;
	
	if($q != ''){
		$result = doQuery("SELECT PkID, NetworkType, NetworkName, NetworkID, Email, SignIns, LastSignIn, LastIP, Level, IsBanned,
							(SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events e WHERE e.OwnerID = u.PkID AND e.IsActive = 1 AND e.IsApproved = 1) as Events
						FROM " . HC_TblPrefix  . "users u
						WHERE (NetworkName LIKE('%".$q."%') OR Email LIKE('%".$q."%'))
						ORDER BY NetworkName 
						LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
		$resultP = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "users WHERE (NetworkName LIKE('%".$q."%') OR Email LIKE('%".$q."%'))");
	}
	
	if(isset($result) && hasRows($result)){
		$x = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($x % 2 == 0) ? ' class="hl_frm"' : '';
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
				<label'.$hl.' for="usrValue_'.$row[0].'"><input name="usrValue" id="usrValue_'.$row[0].'" type="radio" onclick="setUser('.$row[0].');" />
				<a href="'.AdminRoot.'/index.php?com=useredit&uID='.$row[0].'" target="_blank"><img src="'.AdminRoot.'/img/icons/user_edit.png" width="16" height="16" alt="" /></a>
				<img src="'.AdminRoot.'/img/logos/'.$net_img.'.png" width="16" height="16" style="float:none;" />
				'.(($row[4] != '') ? '<a href="mailto:'.$row[4].'">'.cOut($row[2]).'</a>' : cOut($row[2])).'
				('.$row[10].' '.$hc_lang_user['Events'].')
				</label>';
			
			++$x;
		}
		$pages = ceil(mysql_result($resultP,0,0)/$resLimit);
		if($pages > 1){
			echo '<div id="pages">';
			for($x = 0;$x < $pages;++$x){
				if($x % 20 == 0 && $x > 0){echo "<br /><br />";}elseif($x > 0){echo "&nbsp;|&nbsp;";}
				echo ($resOffset != $x) ? '<a href="javascript:;" onclick="searchUsers('.$x.');">'.($x + 1).'</a>' : '<b>'.($x + 1).'</b>';
			}
			echo '</div>';
		}
	} else {
		echo '<span class="no_usr">' . $hc_lang_user['NoUsersSearch'] . '</span>';
	}