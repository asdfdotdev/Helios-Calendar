<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/newsletter.php');

	if(isset($_GET['nID']) && is_numeric($_GET['nID'])){
		$nID = 0;
		$name = "";
		$source = "";
		$helpDoc = "Creating_Templates";
		$helpText = $hc_lang_news['InstructEditNE'];
		
		if($_GET['nID'] > 0){
			$nID = $_GET['nID'];
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . $nID);
			if(hasRows($result)){
				$name = mysql_result($result, 0, "TemplateName");
				$source = mysql_result($result, 0, "TemplateSource");
				$helpDoc = "Editing_Templates";
				$helpText = $hc_lang_news['InstructEditNE'];
			}//end if
		}//end if	
		
		appInstructions(0, $helpDoc, $hc_lang_news['TitleEditN'], $helpText);	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function togThis(doTog, doLink){
				if(document.getElementById(doTog).style.display == 'none'){
					document.getElementById(doTog).style.display = 'block';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_news['HideVariables'];?>';
				} else {
					document.getElementById(doTog).style.display = 'none';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_news['ShowVariables'];?>';
				}//end if
			}//end togThis()
		
		function chkFrm(){
		dirty = 0;
		warn = "Template could not be saved for the following reason(s):";
			
			if(document.frm.tempname.value == ''){
				dirty = 1;
				warn = warn + '\n*Template Name is Required';
			}//end if
			
			if(tinyMCE.get('tempsource').getContent() == ''){
			     dirty = 1;
			     warn = warn + '\n*Template Source is Required'
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n\nPlease complete the form and try again.');
				return false;
			} else {
				return true;
			}//end if
		}//end chkFrm()
		//-->
		</script>
		<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/NewsletterEditAction.php";?>" onsubmit="return chkFrm();">
		<input type="hidden" name="nID" id="nID" value="<?php echo cOut($nID);?>" />
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_news['NewsTemplate'];?></legend>
			<div class="frmReq">
				<label for="tempname"><?php echo $hc_lang_news['NameLabel'];?></label>
				<input name="tempname" id="tempname" type="text" size="40" maxlength="250" value="<?php echo cOut($name);?>" />
			</div>
			<div class="frmReq">
				<label><?php echo $hc_lang_news['VariableLabel'];?></label>
					<a id="newsLink" href="javascript:;" onclick="togThis('newsVars', 'newsLink');"><?php echo $hc_lang_news['ShowVariables'];?></a>
					<br /><br />
					<div id="newsVars" style="border:solid 1px #CCCCCC;display:none;background:#EFEFEF;">
						<div style="float:left;width:20%;padding:5px;line-height:25px;font-weight:normal;">
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[events]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarEvents']);?> [events]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[firstname]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarFname']);?> [firstname]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[lastname]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarLname']);?> [lastname]
						</div>
						<div style="float:left;width:20%;padding:5px;line-height:25px;font-weight:normal;">
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[email]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarEmail']);?> [email]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[zip]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarPostal']);?> [zip]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[billboard]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarBillboard']);?> [billboard]
						</div>
						<div style="float:left;width:25%;padding:5px;line-height:25px;font-weight:normal;">
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[billboard-today]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarBillToday']);?> [billboard-today]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[most-viewed]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarMostViewed']);?> [most-viewed]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[event-count]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarEventCount']);?> [event-count]
						</div>
						<div style="float:left;width:25%;padding:5px;line-height:25px;font-weight:normal;">
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[calendarurl]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarCalURL']);?> [calendarurl]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[unsubscribe]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarUnsub']);?> [unsubscribe]
						<br />
						<?php appInstructionsIcon($hc_lang_news['NewsVariable'], "&lt;b&gt;" . $hc_lang_news['Variable'] . "&lt;/b&gt; &lt;i&gt;[editregistration]&lt;/i&gt;&lt;br /&gt;" . $hc_lang_news['VarEditReg']);?> [editregistration]
						</div>
						<div style="clear:both;"></div>
					</div>
			</div>
			<?php makeTinyMCE('tempsource', '100%', 'advanced', cOut($source));?>
		</fieldset>
		<br />
		<input type="submit" name="submit" value=" <?php echo $hc_lang_news['SaveTemplate'];?> " class="button" />&nbsp;&nbsp;
		<input type="reset" name="reset" id="reset" value=" <?php echo $hc_lang_news['ResetTemplate'];?> " class="button" />
		</form>
<?php
	} else {
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1, $hc_lang_news['Feed07']);
					break;
				case "2" :
					feedback(1, $hc_lang_news['Feed08']);
					break;
				case "3" :
					feedback(1, $hc_lang_news['Feed08']);
					break;
			}//end switch
		}//end if
		
		appInstructions(0, "Editing_Templates", $hc_lang_news['TitleEditN'], $hc_lang_news['InstructEditNL']);	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_news['Valid20'] . "\\n\\n          " . $hc_lang_news['Valid21'] . "\\n          " . $hc_lang_news['Valid22'];?>')){
			document.location.href = '<?php echo CalAdminRoot . "/components/NewsletterEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	
	function templatePreview(pID){
		window.open('<?php echo CalAdminRoot;?>/components/NewsletterPreview.php?pID=' + pID,'hc_preview','location=1,status=1,scrollbars=1,width=800,height=600,left='+(screen.availWidth/2-400)+',top='+(screen.availHeight/2-300));
	}//end templatePreview()
	//-->
	</script>
<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 AND PkID ORDER BY TemplateName");	?>
	<div class="newsletterList"><?php echo $hc_lang_news['TemplateName'];?>&nbsp;&nbsp;(<a href="<?php echo CalAdminRoot;?>/index.php?com=newsletteredit&amp;nID=0" class="legend"><?php echo $hc_lang_news['NewTemplate'];?></a>)</div>
<?php
	if(hasRows($result)){
			$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="newsletterTitle<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[1])?></div>
				<div class="newsletterTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="javascript:;" onclick="templatePreview('<?php echo $row[0];?>');" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconView.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="<?php echo CalAdminRoot;?>/index.php?com=newsletteredit&amp;nID=<?php echo cOut($row[0]);?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:doDelete('<?php echo cOut($row[0]);?>');" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>
		<?php 	$cnt++;
			}//end while
		} else {	?>
			<div class="frmOpt">
				<?php echo "<br />" . $hc_lang_news['NoTemplates'] . "<br /><br />";?>
			</div>
<?php 	}//end if
	}//end if	?>