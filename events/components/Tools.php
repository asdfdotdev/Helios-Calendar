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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/tools.php');	?>
	<script language="JavaScript" type="text/javascript">
	//<!--
	function togSynd(which){
		if(which == 1){
			document.getElementById('syndMap').style.display = 'none';
			document.getElementById('syndEvents').style.display = 'block';
		} else {
			document.getElementById('syndMap').style.display = 'block';
			document.getElementById('syndEvents').style.display = 'none';
		}//end if
	}//end togSynd
	//-->
	</script>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['RSSLabel'];?></legend>
		<a href="<?php echo CalRoot;?>/index.php?com=rss" class="eventMain"><?php echo $hc_lang_tools['RSSLink'];?></a>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Syndication'];?></legend>
	<?php
		if($hc_cfg69 == 1){
			echo '<div class="syndMenu"><div class="hc_align"><b>' . $hc_lang_tools['View'] . '</b></div> ';
			echo '&nbsp;<a href="javascript:;" onclick="togSynd(1);" class="eventMain">' . $hc_lang_tools['Events'] . '</a> | ';
			echo '<a href="javascript:;" onclick="togSynd(0);" class="eventMain">' . $hc_lang_tools['Map'] . '</a>&nbsp;</div>';
			
			echo '<div id="syndMap" style="display:none;">';
			echo '<ol>';
			echo '<li>' . $hc_lang_tools['SyndM1'] . '</li>';
			echo '<li>' . $hc_lang_tools['SyndM2'] . '</li>';
			echo '<li>' . $hc_lang_tools['SyndM3'] . '</li>';
			echo '<li>' . $hc_lang_tools['SyndM4'] . '</li>';
			echo '</ol>';

			echo '<div class="toolsHeader"><b>' . $hc_lang_tools['HeaderCode'] . '</b> [ <a href="' . CalRoot . '/js/map.css" class="eventMain" target="_blank">' . $hc_lang_tools['Template'] . '</a> ]</div>';
			echo '<div class="frmOpt">';
			echo '<textarea style="width:95%;height:55px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">';
echo '&lt;link rel="stylesheet" type="text/css" href="' . CalRoot . '/js/map.css" /&gt;';
echo "\n" . '&lt;script language="JavaScript" type="text/JavaScript" src="http://maps.google.com//maps?file=api&amp;v=2&amp;key=GM_Key"&gt;&lt;/script&gt;';
			echo '</textarea></div><br />';

			echo '<div class="toolsHeader"><b>' . $hc_lang_tools['MapCode'] . '</b></div>';
			echo '<div class="frmOpt">';
			echo '<textarea style="width:95%;height:55px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">';
echo '&lt;div id="hc_GmapLoc"&gt;&lt;/div&gt;';
echo "\n" . '&lt;script language="JavaScript" type="text/JavaScript" src="' . CalRoot . '/cache/lmap_embed.js"&gt;&lt;/script&gt;';
			echo '</textarea></div><br />';

			echo '<div class="toolsHeader"><b>' . $hc_lang_tools['FootCode'] . '</b></div>';
			echo '<div class="frmOpt">';
			echo '<textarea style="width:95%;height:75px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">';
echo '&lt;script language="JavaScript" type="text/JavaScript"&gt;' . "\n" . '//<!--' . "\n" . 'buildGmap();' . "\n" . '//-->' . "\n" . '&lt;/script&gt;';
			echo '</textarea></div></div>';
		}//end if

		echo '<div id="syndEvents">';
		echo '<ol>';
		echo '<li>' . $hc_lang_tools['Synd1'] . ' <a href="' . CalRoot . '/js/syndication.css" class="eventMain" target="_blank">' . $hc_lang_tools['Synd1B'] . '</a>.</li>';
		echo '<li>' . $hc_lang_tools['Synd2'] . '</li>';
		echo '<li>' . $hc_lang_tools['Synd3'] . '</li>';
		echo '</ol>';
		echo '<div class="toolsHeader"><b>' . $hc_lang_tools['Stylesheet'] . '</b> [ <a href="' . CalRoot . '/js/syndication.css" class="eventMain" target="_blank">' . $hc_lang_tools['Template'] . '</a> ]</div>';
		echo '<div class="frmOpt">';
		echo '<input name="stylesheet" id="styleshset" style="width:95%;" onfocus="this.select();" value=\'&lt;link rel="stylesheet" type="text/css" href="' . CalRoot . '/js/syndication.css" /&gt;\' />';
		echo '</div><br />';
		echo '<div class="toolsHeader"><b>' . $hc_lang_tools['Code'] . '</b></div>';
		echo '<textarea style="width:95%;height:135px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">&lt;script language="JavaScript" type="text/JavaScript" src="' . CalRoot . '/js/syndication.php?s=0&amp;z=10&amp;t=1"&gt;';

echo "\n//<!--
/*	" . $hc_lang_tools['CodeComment'] . "
	s = " . $hc_lang_tools['CodeS'] . ", 0 = " . $hc_lang_tools['CodeS0'] . ", 1 = " . $hc_lang_tools['CodeS1'] . ", 2 = " . $hc_lang_tools['CodeS2'] . ", 3 = " . $hc_lang_tools['CodeS3'] . "
	z = " . $hc_lang_tools['CodeZ'] . "
	t = " . $hc_lang_tools['CodeT'] . ", 1 = " . $hc_lang_tools['CodeT1'] . ", 0 = " . $hc_lang_tools['CodeT0'] . "
*/
//-->\n";
		echo '&lt;/script&gt;</textarea></div>';?>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['OpenSearch'];?></legend>
		<?php echo $hc_lang_tools['OSDesc'];?>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['MobileSite'];?></legend>
		<a href="<?php echo MobileRoot;?>" class="eventMain"><b><?php echo MobileRoot;?></b></a>
		<br /><br />
		<?php echo $hc_lang_tools['MobileDesc'];?>
		<br />
		(<?php echo $hc_lang_tools['MobileReq'];?>)
	</fieldset>