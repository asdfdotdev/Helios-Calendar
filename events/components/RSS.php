<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/rss.php');
	
	$rID = 0;
	if(isset($_GET['rID']) && is_numeric($_GET['rID'])){
		$rID = $_GET['rID'];
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function updateLink(){
			var catStr = "";
			var cityStr = "";
			var doBoth = false;
			if(!validateCheckArray('frmEventRSS','catID[]',1) > 0){
				catStr = 'l=' + checkUpdateString('frmEventRSS', 'catID[]');
				doBoth = true;
			}//end if
			if(!validateCheckArray('frmEventRSS','cityName[]',1) > 0){
				cityStr = doBoth == true ? '&' : '';
				cityStr += 'c=' + checkUpdateString('frmEventRSS', 'cityName[]');
			}//end if
			document.frmEventRSS.rssLink.value = '<?php echo CalRoot;?>/rss.php?' + catStr + cityStr;
		}//end updateLink()
	//-->
	</script>
	<div class="rssTitle"><?php echo $hc_lang_rss['OneClick'];?></div>
	<div class="rssSynd">
		<a href="http://feeds.my.aol.com/add.jsp?url=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_aol.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://fusion.google.com/add?feedurl=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_google.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://www.netvibes.com/subscribe.php?url=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_netvibes.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://www.rojo.com/add-subscription?resource=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_rojo.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
	</div>
	<div class="rssSynd">		
		<a href="http://www.bloglines.com/sub/<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_bloglines.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://kinja.com/checksiteform.knj?pop=y&amp;add=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_kinja.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_newsgator.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://www.live.com/?add=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_live.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
	</div>
	<div class="rssSynd">	
		<a href="http://del.icio.us/post?url=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_delicious.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://my.msn.com/addtomymsn.armx?id=rss&amp;ut=<?php echo CalRoot;?>/rss.php&amp;ru=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_msn.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://www.pageflakes.com/subscribe.aspx?url=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_pageflakes.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
		<a href="http://add.my.yahoo.com/content?url=<?php echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/synd_yahoo.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
	</div>
	
	<form name="frmEventRSS" id="frmEventRSS" method="post" action="<?php echo CalRoot;?>/rss.php" target="_blank">
	<div class="rssTitle"><?php echo $hc_lang_rss['CreateCustom'];?></div>
	<?php echo $hc_lang_rss['CreateInstruct'];?><br /><br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['Cities'];?></legend>
		<div class="frmOpt">
			<table cellpadding="0" cellspacing="0" border="0"><tr>
	<?php 	$cities = getCities();
			$cnt = 0;
			foreach($cities as $val){
				if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
				if($val != ''){	?>
					<td><label for="cityName_<?php echo str_replace(" ","_",$val);?>" class="category"><input onclick="updateLink();" name="cityName[]" id="cityName_<?php echo str_replace(" ","_",$val);?>" type="checkbox" value="<?php echo urlencode($val);?>" class="noBorderIE" /><?php echo $val;?></label></td>
	<?php 		}//end if
				$cnt++;
			}//end foreach	?>
			</tr></table>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['Categories'];?></legend>
		<div class="frmOpt">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
	<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
			$cnt = 0;
			
			while($row = mysql_fetch_row($result)){
				if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
				<td><label for="catID_<?php echo $row[0];?>" class="category"><input onclick="updateLink();" name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
		<?php 	$cnt = $cnt + 1;
			}//end while?>
		</tr>
		</table>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['PasteInstruct'];?></legend>
		<div class="frmOpt">
			<input readonly="readonly" name="rssLink" id="rssLink" type="text" style="width:95%;" maxlength="200" value="<?php echo CalRoot;?>/rss.php" />
		</div>
	</fieldset>
	<br />
	<input name="reset" id="reset" type="reset" value=" Start Over" />
	</form>
	<br /><br />
	<div class="rssTitle"><?php echo $hc_lang_rss['Guidelines'];?></div>
	<?php echo $hc_lang_rss['GuidelineText'];?>
	<br />
	<script language="JavaScript" type="text/JavaScript">
	//<!--
<?php 	$eParts = explode("@", CalAdminEmail);?>
		var ename = '<?php echo $eParts[0];?>';
		var edomain = '<?php echo $eParts[1];?>';
		document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain"><?php echo CalAdmin;?></a><br />');
	//-->
	</script>