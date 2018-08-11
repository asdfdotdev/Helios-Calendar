<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	$token = set_form_token(1);
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 0");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_result($result,0,0) : 0;
	$hc_Side[] = array(AdminRoot . '/components/MailSubEditAction.php?dID=uc&a=1&tkn='.$token,'user_delete.png',$hc_lang_news['DeleteNoConfirm'] . ' <b>' . $num . '</b>',0);
	$hc_Side[] = array(AdminRoot . '/components/MailSubDownload.php?tkn='.$token,'download_csv.png',$hc_lang_news['DownloadSub'],0);

	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$term = $save = $queryS = '';
	if(isset($_GET['s']) && $_GET['s'] != ''){
		$term = cIn(cleanQuotes(strip_tags($_GET['s'])));
		$save = '&s='.$term;
		$queryS = " AND (FirstName LIKE('%".$term."%') OR LastName LIKE('%".$term."%') OR Email LIKE('%".$term."%'))";
	}
	
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix  . "subscribers WHERE IsConfirm = 1 $queryS");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	$resOffset = ($pages <= $resOffset && $pages > 0) ? $pages - 1 : $resOffset;
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed05']);
				break;
			case "2" :
				feedback(1, $hc_lang_news['Feed18']);
				break;
		}
	}
	
	appInstructions(0, "Manage_Subscribers", $hc_lang_news['TitleBrowseS'], $hc_lang_news['InstructBrowseS']);

	echo '
		<fieldset style="border:0px;">
			<label><b>'.$hc_lang_news['ResPer'].'</b></label>
			<span class="output">';
			
	for($x = 25;$x <= 100;$x = $x + 25){
		echo ($x > 25) ? '&nbsp;|&nbsp;' : '';			
		echo ($x != $resLimit) ?
			'<a href="'.AdminRoot.'/index.php?com=submngt&amp;p='.$resOffset.'&amp;a='.$x.$save.'">'.$x.'</a>':
			'<b>'.$x.'</b>';
	}
		
	echo '
			</span>
			<label><b>'.$hc_lang_news['Page'].'</b></label>
			<span class="output">';

	$x = ($resOffset - $resDiff > 0) ? $resOffset - $resDiff : 0;
	$cnt = 0;

	echo ($resOffset > $resDiff) ? '<a href="'.AdminRoot.'/index.php?com=submngt&p=0&a='.$resLimit.$save.'">1</a>&nbsp;...&nbsp;' : '';

	while($cnt <= ($resDiff * 2) && $x < $pages){
		echo ($cnt > 0) ? ' | ' : '';
		echo ($resOffset != $x) ?
			'<a href="'.AdminRoot.'/index.php?com=submngt&amp;p='.$x.'&amp;a='.$resLimit.$save.'">'.($x + 1).'</a>':
			'<b>' . ($x + 1) . '</b>';
		++$x;
		++$cnt;
	}

	echo ($resOffset < ($pages - ($resDiff + 1))) ? '&nbsp;...&nbsp;<a href="'.AdminRoot.'/index.php?com=submngt&p='.($pages - 1).'&a='.$resLimit.$save.'">'.$pages.'</a>' : '';
	echo '
			</span>
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<input name="filter" id="filter" type="text" size="30" maxlength="50" value="'.$term.'" />
				<input name="filter_go" id="filter_go" type="button" value="'.$hc_lang_news['FilterNewsSub'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=submngt&p=0&a='.$resLimit.'&s=\'+document.getElementById(\'filter\').value;" />
			</span>
			'.(($term != '') ? '<label>&nbsp;</label><span class="output"><a href="'.AdminRoot.'/index.php?com=submngt&p=0&a='.$resLimit.'">'.$hc_lang_news['AllNewsLink'].'</a></span>' : '').'
		</fieldset>';

	$result = doQuery("SELECT PkID, FirstName, LastName, Email, RegisteredAt FROM " . HC_TblPrefix  . "subscribers WHERE IsConfirm = 1 $queryS ORDER BY LastName, FirstName LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:35%;">'.$hc_lang_news['Name'].'</div>
				<div style="width:40%;">'.$hc_lang_news['Emailb'].'</div>
				<div style="width:15%;">'.$hc_lang_news['Registered'].'</div>
				<div style="width:10%;">&nbsp;</div>
			</li>';
		
		$cnt = 1;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 0) ? ' hl':'';
			echo '
			<li class="row'.$hl.'">
				<div style="width:35%;">'.cOut(trim($row[2].', '.$row[1])).'</div>
				<div class="txt" title="'.cOut($row[3]).'" style="width:40%;">'.cOut($row[3]).'&nbsp;</div>
				<div style="width:15%;">'.stampToDate($row[4], $hc_cfg[24]).'</div>
				<div class="tools" style="width:10%;">
					<a href="' . AdminRoot . '/index.php?com=subedit&amp;uID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</ul>
		
		<script>
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_news['Valid08'].'\\n\\n          '.$hc_lang_news['Valid09'].'\\n          '.$hc_lang_news['Valid10'].'"))
				document.location.href = "'.AdminRoot.'/components/MailSubEditAction.php?dID=" + dID + "&tkn='.$token.'";
		}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_news['NoSub'] . '</p>';
	}
?>