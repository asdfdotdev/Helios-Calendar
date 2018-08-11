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
	/**
	 * Simple query and error handling wrapper for mysql_query(). Outputs MySQL errors in a "human friendly" CSS styled dialog.
	 *
	 * @param string $query Query string to pass to the active MySQL server/database.
	 * @return object
	 */
	function doQuery($query){
		$result = mysql_query($query);
		
		if(!$result){
			handleError(mysql_errno(), mysql_error());
		}//end if
		return $result;
	}//end doQuery()
	/**
	 * Filter "Smart Quotes" and em dash (&mdash). Replace with valid quotes and dash characters.
	 *
	 * @param string $value string to filter
	 * @param binary $filter [optional] 0 = exact replace, 1 = change double quotes to single, default:1
	 * @return string
	 */
	function cleanQuotes($value,$filter = 1){
		$badChars = array('/' . chr(145) . '/','/' . chr(146) . '/','/' . chr(147) . '/','/' . chr(148) . '/','/' . chr(151). '/',"/\\" . chr(92) . "/");
		$goodChars = ($filter == 1) ? array("'", "'", "'", "'", '-', "") : array("'", "'", "\"", "\"", '-', "");
		$value = preg_replace($badChars,$goodChars,$value);

		return $value;
	}//end if
	/**
	 * Remove all line breaks and carriage returns.
	 *
	 * @param string $value string to filter
	 * @return string
	 */
	function cleanBreaks($value){
		$badChars = array('/' . chr(10) . '/','/' . chr(13) . '/','/\\\n/','/\\\r/');
		$goodChars = array("", "", "", "",);
		$value = preg_replace($badChars,$goodChars,$value);
		
		return $value;
	}//end cleanBreaks()
	/**
	 * Cleans string for use in MySQL query.
	 *
	 * @param string $value string to filter
	 * @param binary $filter [optional] 0 = just escape string, 1 = also replace double quotes with single default:1
	 * @return string
	 */
	function cIn($value,$filter = 1) {
		$value = ($filter == 1) ? str_replace("\"", "'", $value) : $value;
		return mysql_real_escape_string($value);
	}//end cIn()
	/**
	 * Cleans string for Output.
	 *
	 * @param string $value string to filter
	 * @return string
	 */
	function cOut($value){
		return stripslashes($value);
	}//end cOut()
	/**
	 * Kills Admin User Session (Including Session for MCImageManager).
	 *
	 * @return void
	 */
	function killAdminSession(){
		global $hc_cfg00;

		//	Purge Known Session
		if(isset($_SESSION['AdminPkID'])){
			doQuery("UPDATE " . HC_TblPrefix . "admin SET Access = NULL WHERE PkID = '" . cIn($_SESSION['AdminPkID']) . "'");
		}//end if

		//	Kill Admin Session Variables
		unset($_SESSION['AdminLoggedIn']);
		unset($_SESSION['AdminPkID']);
		unset($_SESSION['AdminFirstName']);
		unset($_SESSION['AdminLastName']);
		unset($_SESSION['AdminEmail']);
		unset($_SESSION['Instructions']);
		unset($_SESSION['hc_SessionReset']);
		unset($_SESSION['hc_whoami']);
		
		//	Kill MCImageManager Session Variables
		unset($_SESSION['isLoggedIn']);
		unset($_SESSION['imagemanager.preview.wwwroot']);
		unset($_SESSION['imagemanager.preview.urlprefix']);
		unset($_SESSION['imagemanager.filesystem.rootpath']);
	}//end killAdminSession()
	/**
	 * Kills OpenID User Session (Public Calendar)
	 *
	 * @return void
	 */
	function killOIDSession(){
		global $hc_cfg00;
		unset($_SESSION['hc_OpenIDPkID']);
		unset($_SESSION['hc_OpenIDShortName']);
		unset($_SESSION['hc_OpenIDLoggedIn']);
		unset($_SESSION['hc_OpenID']);
	}//end killOIDSession()
	/**
	 * Changes Admin User Session ID and resets timeout. Session ID rotates at random intervals and compares against known Session ID & IP for the user to increase security.
	 *
	 * @return void
	 */
	function startNewSession(){
		global $hc_cfg00;
		$aUser = (isset($_SESSION['AdminPkID'])) ? cIn($_SESSION['AdminPkID']) : 0;
		
		$resultAS = doQuery("SELECT Access FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $aUser . "'");
		$knownSession = (hasRows($resultAS)) ? mysql_result($resultAS,0,0) : NULL;
		if($knownSession != md5(session_id())){
			killAdminSession();
		} else {
			$_SESSION['hc_SessionReset'] = (date("U") + mt_rand(60,900));
		}//end if
		
		$old_session = session_id();
		session_regenerate_id();
		$new_session = session_id();
		session_write_close();
		session_id($new_session);
		session_name($hc_cfg00);
		session_start();
		$_SESSION['hc_whoami'] = md5($_SERVER['REMOTE_ADDR'] . session_id());

		if(isset($_COOKIE[$old_session])) {
		    setcookie($old_session, '', time()-86400, '/');
		}//end if

		doQuery("UPDATE " . HC_TblPrefix . "admin SET Access = '" . cIn(md5(session_id())) . "' WHERE PkID = '" . $aUser . "'");
	}//end createNewSession()
	/**
	 * Action page security check. Allows valid requests to execute based on location of request (public/admin).
	 *
	 * @param binary $admin [optional] 0 = public calendar, 1 = admin console, default:1
	 * @return void
	 */
	function checkIt($admin = 0){
		global $hc_cfg00;
		
		if($admin == 1){
			if(isset($_SESSION['AdminLoggedIn'])) {
				return;
			}//end if
			header("Location: " . CalRoot);
			exit();
		} else {
			if(isset($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], CalRoot)){
				return;
			}//end if
			header("Location: ../");
			exit();
		}//end if
	}// checkIt()
	/**
	 * Outputs user friendly error dialog (CSS).
	 *
	 * @param int $errNo
	 * @return string
	 */
	function handleError($errNo, $errMsg){
		//	uncomment next line for debugging
		//	echo "<br />---------------------------------------<br /><b>Error #:</b> " . $errNo . "<br /><b>Message:</b> " .$errMsg . "<br />---------------------------------------<br /><br />";
		switch($errNo){
			//	no database
			case 1046:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"Connection to the Helios database is unavailable.<br /><br />Please verify your globals.php file settings are correct. Specifically the name of the database you are using. If you have not yet done so, please run the <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Calendar Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?php
				exit();
				break;
			//	no table
			case 1146:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"Connection to a required Helios datatable is unavailable.<br /><br />If you have not yet done so, please run the <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Calendar Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?php
				exit();
				break;
			default:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"<div style=\"text-align:left;\">Helios Calendar was unable to process a database command. The following error was received from your MySQL server.<br /><blockquote>" . $errNo . ' - ' . $errMsg . "</blockquote><br />For assistance contact your Helios Calendar Administrator.</div>");
			?></div><?php
				exit();
				break;
		}//end switch
	}//end handleError()
	/**
	 * Checks to see if the MySQL result set contains more than a given number of records.
	 *
	 * @param object $result
	 * @param int $min [optional] default:0
	 * @return binary
	 */
	function hasRows($result,$min = 0){
		$chk_row_cnt = mysql_num_rows($result);
		return ($chk_row_cnt > $min) ? 1 : 0;
	}//end hasRows()
	/**
	 * TinyMCE wrapper. Creates varying TinyMCE editor based on parameters.
	 *
	 * @param string $textName name and id of the textarea HTML entity to convert to TinyMCE editor.
	 * @param string $width can contain absolute "550px" or relative "80%" values. default:550px
	 * @param string $style advanced = admin editor (all editor tools and HTML tags), simple = public editor (fewer editor tools and subset of HTML tags). default:simple
	 * @param string $passContent contents of the editor. can include any text content.
	 * @param binary $override [optional] manual override to disable the editor, 0 = use preference setting, 1 = override setting & disable, default: 0.
	 * @return void
	 */
	function makeTinyMCE($textName, $width = '550px', $style = 'simple', $passContent = '', $override = 0){
		global $hc_cfg24, $hc_cfg30, $hc_lang_config;
		
		echo '<textarea name="' . $textName . '" id="' . $textName . '" style="width:' . $width . ';';
		echo ($hc_cfg30 == 0 || $override == 1) ? 'height: 350px;"' : 'height: 350px;"';
		echo ' rows="15" cols="55">' . $passContent . '</textarea>';
		
		if($hc_cfg30 == 1 && $override == 0){?>
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="<?php echo CalRoot;?>/includes/tiny_mce/tiny_mce_gzip.js"></script>
		<script type="text/javascript">
		tinyMCE_GZ.init({
			plugins :  "imagemanager,spellchecker,advhr,advimage,advlink,advlist,contextmenu,fullscreen,inlinepopups,insertdatetime,layer,media,paste,preview,searchreplace,style,table,wordcount,xhtmlxtras",
			themes : "advanced",
			languages : "en",
			disk_cache : true,
			debug : false
		});
		</script>
		<script language="javascript" type="text/javascript">
		//<!--
			tinyMCE.init({
				setup : function(ed) {
				   ed.onInit.add(function() {
					  ed.settings.file_browser_callback = null;
				   });
				},
				cleanup : true,
	<?php		echo ($hc_lang_config['Direction'] == 0) ? 'directionality : "rtl",' : '';?>
				mode : "exact",
				editor_selector : "<?php echo $textName;?>",
				theme : "advanced",
				skin : "o2k7",
				skin_variant : "silver",
				elements : "<?php echo $textName;?>",
				entity_encoding : "raw",
				plugins :  "imagemanager,spellchecker,advhr,advimage,advlink,advlist,contextmenu,emotions,fullscreen,inlinepopups,layer,media,paste,preview,searchreplace,style,tabfocus,table,wordcount,xhtmlxtras",
	<?php 	if($style == "advanced"){	?>
				theme_advanced_buttons1 : "fontsizeselect,|,fontselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,outdent,indent,sup,sub,|,replace",
				theme_advanced_buttons2 : "pastetext,pasteword,|,link,unlink,anchor,|,image,insertimage,media,charmap,emotions,|,forecolor,backcolor,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,removeformat,cleanup,|,undo,redo",
				theme_advanced_buttons3 : "tablecontrols,visualaid,insertlayer,moveforward,movebackward,absolute,|,advhr,|,code,|,fullscreen,preview,|,spellchecker,|,help",
				theme_advanced_buttons4 : "",
	<?php 	} else {	?>
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,image,separator,undo,redo,code,spellchecker,pastetext,pasteword",
				theme_advanced_buttons2 : "",
				valid_elements : ""
				+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
				  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
				  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
				  +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
				+"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
				  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
				  +"|onmouseover|onmouseup|style|title],"
				+"br[class|clear<all?left?none?right|id|style|title],"
				+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
				  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
				  +"|onmouseout|onmouseover|onmouseup|style|title],"
				+"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
				  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
				+"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title],"
				+"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title],"
				+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
				  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
				  +"|onmouseout|onmouseover|onmouseup|style|title],"
				+"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title],"
				+"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
				  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
				+"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
				+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
				  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
				  +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
				+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
				  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|src|style|title|usemap|vspace|width],"
				+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
				  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
				  +"|onmouseout|onmouseover|onmouseup|style|title],"
				+"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
				  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
				  +"|value],"
				+"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title],"
				+"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|start|style|title|type],"
				+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
				  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
				  +"|onmouseout|onmouseover|onmouseup|style|title],"
				+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
				  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
				  +"|onmouseover|onmouseup|style|title|width],"
				+"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title],"
				+"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title],"
				+"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"style[dir<ltr?rtl|lang|media|title|type],"
				+"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title],"
				+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
				  +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
				  +"|style|summary|title|width],"
				+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
				  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
				  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
				  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
				  +"|style|title|valign<baseline?bottom?middle?top|width],"
				+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
				  +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title|valign<baseline?bottom?middle?top],"
				+"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
				  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
				+"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
				  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
				+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
				  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
				  +"|onmouseup|style|title|type],"
				+"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
				  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
				  +"|title]",
	<?php 	}//end if	?>
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				theme_advanced_resize_horizontal : false,
				apply_source_formatting : false,
				relative_urls : false,
				remove_script_host : false,
				spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"
			});
		//-->
		</script>
		<div style="height:0px;clear:both;">&nbsp;</div>
		<!-- /tinyMCE -->
	<?php
		}//end if
	}//end makeTinyMCE()
	/**
	 * Create styled feedback dialog.
	 *
	 * @param int $type 1 = Success Dialog, 2 = Warning Dialog, 3 = Critical Error Dialog, default:1
	 * @param string $fmessage Message output within the dialog.
	 * @return void
	 */
	function feedback($type, $fmessage){
		$fType = "info";
		$fIcon = "iconSuccess.png";
		switch($type){
			case 2:
				$fType = "warning";
				$fIcon = "iconCaution.png";
				break;
			case 3:
				$fType = "error";
				$fIcon = "iconError.png";
				break;
		}//end switch	?>
		<div class="<?php echo $fType;?>">
			<img src="<?php echo CalRoot;?>/images/feedback/<?php echo $fIcon;?>" alt="" border="0" style="vertical-align:middle;" />&nbsp;<?php echo $fmessage;?>
		</div><br />
<?php
	}//end feedback
	/**
	 * Create styled instruction dialog.
	 *
	 * @param int $noSwitch 0 = Switching Not Blocked. User can toggle full dialog/title, 1 = Dialog toggle blocked
	 * @param string $codex Refresh Software Documentation Page. For no link use blank value = ''
	 * @param string $title Title of Instruction Dialog, always visible regardless of toggle state.
	 * @param string $message Body of Instruction Dialog, visibility determined by user.
	 * @return void
	 */
	function appInstructions($noSwitch, $codex, $title, $message){
		global $hc_cfg00;
		
		if($_SESSION['Instructions'] == 1){
			$mvIcon = 'iconCollapse';
			$optStyle = '';
			$msgTxt = '<div style="padding: 5px 0px 5px 10px;">' . $message . '</div>';
		} else {
			$mvIcon = 'iconExpand';
			$optStyle = 'style="height: 16px;"';
			$msgTxt = '';
		}//end if
		echo '<div class="instructions" ' . $optStyle . '>';
		echo '<div class="instTitle">' . $title . '</div>';
		echo '<div class="instTools">';
		echo ($codex != '') ? '<a href="http://www.refreshmy.com/documentation/index.php?title=' . $codex . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconHelp.png" width="16" height="16" alt="" border="0" /></a>' : '';
		echo ($noSwitch == 0) ? '&nbsp;&nbsp;<a href="' . CalAdminRoot . '/' . HC_InstructionsSwitch . '" class="main"><img src="' . CalAdminRoot . '/images/icons/' . $mvIcon . '.png" width="16" height="16" alt="" border="0" /></a>' : '';
		echo '</div>';
		echo $msgTxt;
		echo '</div>';
	}//end instruction()
	/**
	 * Create context relevent instructions icon (i).
	 *
	 * @param string $title Title of popup dialog.
	 * @param string $message Body of popup dialog.
	 * @return void
	 */
	function appInstructionsIcon($title, $message){
	?>	<a onmouseover="this.T_TITLE='<?php echo $title;?>';this.T_SHADOWCOLOR='#3D3F3E';return escape('<?php echo $message;?>')" href="javascript:;" class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/icons/iconInfo.png" width="16" height="16" alt="" border="0" style="vertical-align:top;" /></a><?php
	}//end calInstructionsIcon()
	/**
	 * Converts MySQL timestamp (YYYY-MM-DD hh:mm:ss) to date string of passed format.
	 *
	 * @link http://php.net/manual/en/function.strftime.php
	 * @param datetime $timeStamp Timestamp to convert.
	 * @param string $dateFormat Note: Format string must include only strftime() supported format parameters.
	 * @return string
	 */
	function stampToDate($timeStamp, $dateFormat){
		$theDate = ($timeStamp != '') ? strftime($dateFormat, strtotime($timeStamp)) : '';
		return $theDate;
	}//end stampToDate()
	/**
	 * Converts MySQL timestamp (YYYY-MM-DD hh:mm:ss) to AP Style date string.
	 *
	 * @uses timeToAP()
	 * @param datetime $timeStamp Timestamp to convert.
	 * @param binary $useYear [optional] 0 = exclude year from output string, 1 = include year in output string, default:1
	 * @return string
	 */
	function stampToDateAP($timeStamp,$useYear = 1){
          $stampParts = explode(" ", $timeStamp);
		$dateParts = explode("-", $stampParts[0]);
		$dateFormat = ($useYear == 1) ? ' %#d, %Y' : ' %#d';

		switch($dateParts[1]){
			case 1:
			case 2:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
				$dateFormat = '%b.' . $dateFormat;
				break;
			default:
				$dateFormat = '%B' . $dateFormat;
				break;
		}//end switch

		switch(stampToDate($timeStamp,'%w')){
			case 0:
				$dateFormat = 'Sun., ' . $dateFormat;
				break;
			case 1:
				$dateFormat = 'Mon., ' . $dateFormat;
				break;
			case 2:
				$dateFormat = 'Tues., ' . $dateFormat;
				break;
			case 3:
				$dateFormat = 'Wed., ' . $dateFormat;
				break;
			case 4:
				$dateFormat = 'Thurs., ' . $dateFormat;
				break;
			case 5:
				$dateFormat = 'Fri., ' . $dateFormat;
				break;
			case 6:
				$dateFormat = 'Sat., ' . $dateFormat;
				break;
		}//end if

		$theDate = strftime($dateFormat, mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));

		if(isset($stampParts[1])){
			$theDate .= ' ' . timeToAp($stampParts[1]);
		}//end if
		
		return $theDate;
	}//end stampToDate()
	/**
	 * Converts 24 hour Time to AP Style time string.
	 * NOTE: This works with 24hr TIME ONLY. Use stampToDateAP() to convert full datetime.
	 *
	 * @param string $timeStamp Time string in 24hr format (HH:MM:SS)
	 * @return string
	 */
	function timeToAP($timeStamp){
		global $hc_cfg31;
		$timeFormat = ($hc_cfg31 == 12) ? '%#I:%M' : '%H:%M';
		$timeParts = explode(":", $timeStamp);
		switch($timeParts[0]){
			case 12:
			case 00:
				if($timeParts[1] == 00){
					$time = ($timeParts[0] == 12) ? 'Noon' : 'Midnight';
					break;
				}//end if
			default:
				$time = strftime($timeFormat, mktime($timeParts[0], $timeParts[1], 0, date("m"), date("d"), date("Y")));
				if($hc_cfg31 == 12){
					$time .= ($timeParts[0] >= 12) ? ' p.m.' : ' a.m.';
				}//end if
		}//end switch
		return $time;
	}//end timeToAP()
	/**
	 * Convert date to MySQL Timestamp. If the passed date is invalid null is returned.
	 *
	 * @param string $theDate Date string. Value must conform to $dateFormat.
	 * @param string $dateFormat Must be: %d/%m/%y, %m/%d/%y or %y/%m/%d (only these formats are supported)
	 * @return string
	 */
	function dateToMySQL($theDate, $dateFormat){
		$theDate = str_replace("%","",$theDate);
		$dateParts = explode('/',$theDate);
		
		$theDate = NULL;
		switch(strToLower($dateFormat)){
			case '%d/%m/%y':
				if(checkdate($dateParts[1],$dateParts[0],$dateParts[2])){
					$theDate = strftime("%Y-%m-%d", mktime(0,0,0,$dateParts[1],$dateParts[0],$dateParts[2]));
				}//end if
				break;
			case '%m/%d/%y' :
				if(checkdate($dateParts[0],$dateParts[1],$dateParts[2])){
					$theDate = strftime("%Y-%m-%d", mktime(0,0,0,$dateParts[0],$dateParts[1],$dateParts[2]));
				}//end if
				break;
			case '%y/%m/%d':
				if(checkdate($dateParts[1],$dateParts[2],$dateParts[0])){
					$theDate = strftime("%Y-%m-%d", mktime(0,0,0,$dateParts[1],$dateParts[2],$dateParts[0]));
				}//end if
				break;
		}//end switch
		
		return $theDate;
	}//end dateToMySQL()
	/**
	 * Output event category checkboxes.
	 *
	 * @param string $frmName Name/ID of the form the checkboxes are a part of (required for Select/Deselect All links & validation).
	 * @param int $columns The number of columns to display.
	 * @param string $query [optional] SQL query used to generate checkbox list (Required Columns: PkID, CategoryName, ParentID, Sort, Selected).
	 * @param binary $showLinks [optional] 0 = Hide Select/Deselect All Links, 1 = Show Links, default:1
	 * @return void
	 */
	function getCategories($frmName, $columns, $query = NULL, $showLinks = 1){
		global $hc_cfg00, $hc_lang_config;
		include('lang/' . $_SESSION['LangSet'] . '/public/core.php');
		
		if(!isset($query)){	
			$query ="SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
					WHERE c.ParentID = 0 AND c.IsActive = 1
					GROUP BY c.PkID
					UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
					WHERE c.ParentID > 0 AND c.IsActive = 1
					GROUP BY c.PkID 
					ORDER BY Sort, ParentID, CategoryName";
		}//end if
		
		$result = doQuery($query);
		$cnt = 1;
		echo '<div class="catCol">';
		while($row = mysql_fetch_row($result)){
			if($cnt > ceil(mysql_num_rows($result) / $columns) && $row[2] == 0){
				echo ($cnt > 1) ? '</div>' : '';
				echo '<div class="catCol">';
				$cnt = 1;
			}//end if
			echo '<label for="catID_' . $row[0] . '" ';
			echo ($row[2] == 0) ? 'class="category"' : 'class="subcategory"';
			echo '><input ';
			echo ($row[4] != '') ? 'checked="checked" ' : '';
			echo 'name="catID[]" id="catID_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" />' . cOut($row[1]) . '</label>';
			++$cnt;
		}//end while
		echo '</div>';
		
		if($cnt > 1 && $showLinks == 1){
			echo '<div class="catCtrl">';
			echo '[ <a class="catLink" href="javascript:;" onclick="checkAllArray(\'' . $frmName . '\', \'catID[]\');">' . $hc_lang_core['SelectAll'] . '</a>';
			echo '&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray(\'' . $frmName . '\', \'catID[]\');">' . $hc_lang_core['DeselectAll'] . '</a> ]';
			echo '</div>';
		}//end if
	}//end getCategories()
	/**
	 * Create an alphabetically sorted array of event location cities. Returned array will include both Custom and Saved Location cities.
	 *
	 * @param binary $admin [optional] 0 = cities for active events only, 1 = cities for all events, default:0
	 * @return array
	 */
	function getCities($admin = 0){
		$sqlWhere = ($admin == 0) ? " e.StartDate >= NOW() AND " : "";
		$result = doQuery("	SELECT e.LocationCity, l.City
							FROM " . HC_TblPrefix . "events as e
								LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
							WHERE " . $sqlWhere . "
								(e.IsActive = 1 AND 
								e.IsApproved = 1)
								OR (l.IsActive = 1)
								GROUP BY LocationCity, City");
		$cities = array();
		while($row = mysql_fetch_row($result)){
			if($row[1] == ''){
				$cities[strtolower($row[0] . $row[1])] = $row[0] . $row[1];
			} else {
				$cities[strtolower($row[1])] = $row[1];
			}//end if
		}//end while
		array_filter($cities);
		ksort($cities);
		array_unique($cities);
		return $cities;
	}//end getCities()
	/**
	 * Create a sorted array of event location postal codes. Returned array will include both Custom and Saved Location cities.
	 *
	 * @param binary $admin [optional] 0 = postal codes for active events only, 1 = postal codes for all events, default:0
	 * @return array
	 */
	function getPostal($admin = 0){
		$sqlWhere = ($admin == 0) ? " e.StartDate >= NOW() AND " : "";
		$result = doQuery("	SELECT e.LocationZip, l.Zip
							FROM " . HC_TblPrefix . "events as e
								LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
							WHERE " . $sqlWhere . "
								(e.IsActive = 1 AND 
								e.IsApproved = 1)
								OR (l.IsActive = 1)
								GROUP BY LocationZip, Zip");
		$postal = array();
		while($row = mysql_fetch_row($result)){
			if($row[1] == ''){
				$postal[strtolower($row[0] . $row[1])] = $row[0] . $row[1];
			} else {
				$postal[strtolower($row[1])] = $row[1];
			}//end if
		}//end while

		array_filter($postal);
		ksort($postal);
		array_unique($postal);
		return $postal;
	}//end getpostal()
	/**
	 * Escape common special characters.
	 *
	 * @param string $textToClean String to filter.
	 * @return string
	 */
	function cleanSpecialChars($textToClean){
		$textToClean = str_replace("&nbsp;", " ", $textToClean);
		$textToClean = str_replace("\\", "\\\\", $textToClean);
		$textToClean = str_replace("'", "\\'", $textToClean);
		$textToClean = str_replace("\"", "\\\"", $textToClean);
		$textToClean = str_replace(",", "\,", $textToClean);
		return $textToClean;
	}//end cleanSpecialChars()
	/**
	 * Replace or remove XML special characters.
	 *
	 * @param string $textToClean string to filter
	 * @param binary $purge [optional] 0 = replace character with valid entity/value, 1 = purage character, default:0
	 * @return string
	 */
	function cleanXMLChars($textToClean, $purge = 0){
		if($purge == 0){
			$textToClean = str_replace("&ndash;", "-", $textToClean);
			$textToClean = str_replace("&nbsp;", " ", $textToClean);
			$textToClean = str_replace(" & ", " &#38; ", $textToClean);
			$textToClean = str_replace("&", "&#38;", $textToClean);
			$textToClean = str_replace("&amp;", "&#38;", $textToClean);
			$textToClean = str_replace("\"", "&quot;", $textToClean);
			$textToClean = str_replace("'", "&#39;", $textToClean);
			$textToClean = str_replace("<", "&amp;lt;", $textToClean);
			$textToClean = str_replace(">", "&amp;gt;", $textToClean);
			$textToClean = str_replace("$", "&#36;", $textToClean);
			$textToClean = str_replace("?", "&#63;", $textToClean);
			$textToClean = str_replace("&ldquo;", "&quot;", $textToClean);
			$textToClean = str_replace("&lsquo;", "&#39;", $textToClean);
			$textToClean = str_replace("&rdquo;", "&quot;", $textToClean);
			$textToClean = str_replace("&rsquo;", "&#39;", $textToClean);
		} else {
			$textToClean = str_replace("&ndash;", "", $textToClean);
			$textToClean = str_replace(" & ", "", $textToClean);
			$textToClean = str_replace("&", "", $textToClean);
			$textToClean = str_replace("\"", "", $textToClean);
			$textToClean = str_replace("'", "", $textToClean);
			$textToClean = str_replace("<", "", $textToClean);
			$textToClean = str_replace(">", "", $textToClean);
			$textToClean = str_replace("$", "", $textToClean);
			$textToClean = str_replace("?", "", $textToClean);
			$textToClean = str_replace(";", "", $textToClean);
			$textToClean = str_replace(":", "", $textToClean);
			$textToClean = str_replace("&nbsp;", " ", $textToClean);
			$textToClean = str_replace("&ldquo;", "", $textToClean);
			$textToClean = str_replace("&lsquo;", "", $textToClean);
			$textToClean = str_replace("&rdquo;", "", $textToClean);
			$textToClean = str_replace("&rsquo;", "", $textToClean);
		}//end if
		return $textToClean;
	}//end cleanXMLChars()
	/**
	 * Calculate number of days between two dates.
	 *
	 * @param datetime $date1
	 * @param datetime $date2
	 * @return int
	 */
	function daysDiff($date1, $date2){
		$stampParts1 = explode(" ", $date1);
		$dateParts1 = explode("-", $stampParts1[0]);		
		$stampParts2 = explode(" ", $date2);
		$dateParts2 = explode("-", $stampParts2[0]);
		$date1 = date("U", mktime(0, 0, 0, $dateParts1[1], $dateParts1[2], $dateParts1[0]));
		$date2 = date("U", mktime(0, 0, 0, $dateParts2[1], $dateParts2[2], $dateParts2[0]));

		$secs = ($date1 > $date2) ? $date1 - $date2 : $date2 - $date1;
		$days = number_format(((($secs / 60) / 60) / 24) + 1,0,',','');
		return $days;
	}//end daysDiff()
	/**
	 * Output hidden form input fields named for fields commonly targeted by spammers.
	 * Will reduce spam submission success rate when CAPTCHA is turned off.
	 *
	 * @return void
	 */
	function fakeFormFields(){
		echo '<input name="name" id="name" type="text" value="" style="display:none;" />';
		echo '<input name="subject" id="subject" type="text" value="" style="display:none;" />';
		echo '<input name="email" id="email" type="text" value="" style="display:none;" />';
		echo '<input name="phone" id="phone" type="text" value="" style="display:none;" />';
		echo '<input name="message" id="message" type="text" value="" style="display:none;" />';
	}//end fakeFormFields()
	/**
	 * Output CAPTCHA JavaScript Validation based on currently active CAPTCHA type and form in use.
	 *
	 * @param int $where Numeric value assigned to form (1 = Event Submission, 2 = Email to Friend, 3 = Event Registration, 4 = Newsletter Sign-up, 5 = OpenID Login, 6 = Post Comment, 7 = Report Comment)
	 * @return void
	 */
	function captchaValidation($where){
		global $hc_captchas,$hc_cfg00,$hc_cfg65;

		if($hc_cfg65 > 0 && in_array($where, $hc_captchas)){
			include('lang/' . $_SESSION['LangSet'] . '/public/core.php');
			if($hc_cfg65 == 1){
				echo 'if(document.getElementById(\'proof\').value == \'\'){' . "\n\t";
				echo 'dirty = 1;' . "\n\t";
				echo 'warn = warn + \'\n' . $hc_lang_core['Authentication'] . '\';' . "\n\t";
				echo '}//end if';
			} elseif($hc_cfg65 == 2){
				echo 'if(document.getElementById(\'recaptcha_response_field\').value == \'\'){' . "\n\t";
				echo 'dirty = 1;' . "\n\t";
				echo 'warn = warn + \'\n' . $hc_lang_core['Authentication'] . '\';' . "\n\t";
				echo '}//end if';
			}//end if
		}//end if
	}//end captchaValidation()
	/**
	 * Output CAPTCHA Challenge based on currently active CAPTCHA type.
	 *
	 * @return void
	 */
	function buildCaptcha(){
		global $hc_cfg00,$hc_cfg65,$hc_cfg67;
		
		if($hc_cfg65 == 1){
			include('lang/' . $_SESSION['LangSet'] . '/public/event.php');
			echo $hc_lang_event['CannotRead'] . '<br /><br />';
			echo '<div class="frmReq"><label for="proof">&nbsp;</label>';
			echo (function_exists('imagecreate')) ?
				'<img src="' . CalRoot . '/includes/captcha.php" width="250" border="0" alt="" class="captcha" />':
				'<span style="font-weight:bold;color:#DC143C;">GD Library Unavailable. Image Cannot Be Created.</span><br />';
			echo '</div>';
			echo '<div class="frmReq">';
			echo '<label for="proof">' . $hc_lang_event['ImageText'] . '</label>';
			echo '<div class="capResponse"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>';
			echo '<div id="capChk">&nbsp;<a href="javascript:;" onclick="testCAPTCHA();" class="eventMain">' . $hc_lang_event['Confirm'] . '</a>&nbsp;</div>';
			echo '</div>';
		} elseif($hc_cfg65 == 2) {
			if($hc_cfg67 != ''){
				include('api/recaptcha/recaptchalib.php');
				$error = '';
				echo '<script type="text/javascript">' . "\n";
				echo 'var RecaptchaOptions = {' . "\n";
				echo 'theme : \'clean\'' . "\n";
				echo ',lang : \'en\'' . "\n";
				echo '};';
				echo '</script>';
				echo '<div class="frmReq"><label>&nbsp;</label>';
				echo recaptcha_get_html($hc_cfg67, $error);
				echo '</div>';
			} else {
				echo '<b>reCAPTCHA API Key Missing.</b>';
			}//end if
		}//end if
	}//end buildCaptcha()
	/**
	 * CAPTCHA Challenge result based user entry for currently active CAPTCHA type. Exits and outputs error message on failed comparison.
	 *
	 * @return void
	 */
	function spamIt($proof, $challenge, $checkMe){
		global $hc_cfg00,$hc_cfg32,$hc_cfg65,$hc_cfg68;
		$spam = 0;
		$active = explode(",", $hc_cfg32);
		
		if(in_array($checkMe, $active)){
			if($hc_cfg65 == 1){
				$spam = ($challenge != md5($proof)) ? 1 : 0;
			} elseif($hc_cfg65 == 2){
				include('../includes/api/recaptcha/recaptchalib.php');
				$resp = recaptcha_check_answer ($hc_cfg68,$_SERVER["REMOTE_ADDR"],$challenge,$proof);
				if(!$resp->is_valid){
					$spam = 1;
				}//end if
			}//end if
			if($spam != 0){
				exit("Your authentication was entered incorrectly. Please use your browsers back button and try again.");
			}//end if
		}//end if
		return;
	}//end spamIt()
	/**
	 * Converts fetched XML document to a usable array.
	 *
	 * @param string $xml XML Document Contents
	 * @return array
	 */
	function xml2array($xml) {
		$theArry = array();
		$regElem = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
		$regAtt = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';
		preg_match_all($regElem, $xml, $elem);
		foreach($elem[1] as $key => $val) {
			$theArry[$key]["name"] = $elem[1][$key];
			if($attributes = trim($elem[2][$key])){
				preg_match_all($regAtt, $attributes, $att);
				foreach ($att[1] as $key => $val){$theArry[$key]["attributes"][$att[1][$key]] = $att[2][$key];}
			}//end if
			$end = strpos($elem[3][$key], "<");
			if($end > 0){
			    $theArry[$key]["value"] = substr($elem[3][$key], 0, $end);
			}//end if
			if(preg_match($regElem, $elem[3][$key])){
			    $theArry[$key]["elements"] = xml2array($elem[3][$key]);
			} else if ($elem[3][$key]) {
			    $theArry[$key]["value"] = $elem[3][$key];
			}//end if
		}//end foreach

		return $theArry;
	}//end xml2array()
	/**
	 * Writes cache files.
	 *
	 * @param int $cID File to be written (Switch = 1:unused, 2:censored.php, 3:meta.php, 4:selCity.php, 5:selPostal.php, default:config.php).
	 * @param int $a [optional] Use events path prefix (needed for Admin Console action pages). 0 = do NOT use prefix, 1 = use prefix, default:0
	 * @return void
	 */
	function rebuildCache($cID = 0, $a = 0){
		global $hc_cfg11, $hc_lang_search;

		$prfx = $a == 0 ? '' : '../events/';
		if(!is_writable(realpath($prfx.'cache/'))){
			exit("/events/cache/ cannot be written to. Please update directory permissions.");
		}//end if
		
		switch($cID){
			case 1:
				//	Tawc Cache Removed
				break;
			case 2:
				if(file_exists(realpath($prfx.'cache/censored.php'))){
					unlink($prfx.'cache/censored.php');
				}//end if
				
				$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 55");
				if(hasRows($result)){
					ob_start();
					$fp = fopen($prfx.'cache/censored.php', 'w');
					fwrite($fp, "<?php\n//\tHelios Censored Words Cache - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$hc_censored_words = array(\n");
					$wordList = explode("\n",mysql_result($result,0,0));
					foreach($wordList as $key => $val){
						fwrite($fp, "$key\t\t=>\t'" . trim($val, "\r\n") . '\',' . "\n");
					}//end foreach
					fwrite($fp, ");?>");
					fclose($fp);
					ob_end_clean();
				}//end if
				break;
			case 3:
				if(file_exists(realpath($prfx.'cache/meta.php'))){
					unlink($prfx.'cache/meta.php');
				}//end if

				ob_start();
				$fp = fopen($prfx.'cache/meta.php', 'w');
				fwrite($fp, "<?php\n//\tHelios Meta Cache - Delete this file when upgrading.\n");
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settingsmeta");
				while($row = mysql_fetch_row($result)){
					fwrite($fp, "\n\$hc_mk" . $row[0] . " = \"" . cOut($row[1]) . "\";");
					fwrite($fp, "\n\$hc_md" . $row[0] . " = \"" . cOut($row[2]) . "\";");
					fwrite($fp, "\n\$hc_pt" . $row[0] . " = \"" . cOut($row[3]) . "\";");
				}//end while
				fwrite($fp, "\n?>");
				fwrite($fp, ob_get_contents());
				fclose($fp);
				ob_end_clean();
				break;
			case 4:
				if(file_exists(realpath($prfx.'cache/selCity.php'))){
					unlink($prfx.'cache/selCity.php');
				}//end if

				ob_start();
				$fp = fopen($prfx.'cache/selCity.php', 'w');
				fwrite($fp, "<?php\n//\tHelios City Select List Cache - Delete this file when upgrading.\n?>\n");

				fwrite($fp, "\n<select name=\"city\" id=\"city\" disabled=\"disabled\">");
				fwrite($fp, "\n<option value=\"\">" . $hc_lang_search['City0'] . "</option>");

				$cities = getCities($hc_cfg11);
				foreach($cities as $val){
					if($val != ''){
						fwrite($fp, "\n<option value=\"" . $val . "\">" . $val . "</option>");
					}//end if
				}//end foreach
				fwrite($fp, "\n</select>");
				fwrite($fp, ob_get_contents());
				fclose($fp);
				ob_end_clean();
				break;
			case 5:
				if(file_exists(realpath($prfx.'cache/selPostal.php'))){
					unlink($prfx.'cache/selPostal.php');
				}//end if

				ob_start();
				$fp = fopen($prfx.'cache/selPostal.php', 'w');
				fwrite($fp, "<?php\n//\tHelios Postal Code Select List Cache - Delete this file when upgrading.\n?>\n");

				fwrite($fp, "\n<select name=\"postal\" id=\"postal\" disabled=\"disabled\">");
				fwrite($fp, "\n<option value=\"\">" . $hc_lang_search['Postal0'] . "</option>");
				$codes = getPostal($hc_cfg11);
				foreach($codes as $val){
					if($val != ''){
						fwrite($fp, "\n<option value=\"" . $val . "\">" . $val . "</option>");
					}//end if
				}//end foreach
				fwrite($fp, "\n</select>");
				fwrite($fp, ob_get_contents());
				fclose($fp);
				ob_end_clean();
				break;
			default:
				if(file_exists(realpath($prfx.'cache/config.php'))){
					unlink($prfx.'cache/config.php');
				}//end if
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings
									WHERE PkID IN (1,2,3,4,7,8,9,10,11,12,13,14,15,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,
												35,40,41,42,43,44,45,48,49,50,51,52,54,53,56,59,62,63,65,66,67,68,69,70,71,72,73,
												74,75,76,77,78,79,80,81,82)
									ORDER BY PkID");
				if(hasRows($result)){
					ob_start();
					$fp = fopen($prfx.'cache/config.php', 'w');
					fwrite($fp, "<?php\n//\tHelios Config Cache - Delete this file when upgrading.\n\n");
					
					while($row = mysql_fetch_row($result)){
						fwrite($fp, "\$hc_cfg" . $row[0] . " = \"" . $row[1] . "\";\n");
					}//end while
					fwrite($fp, "\$hc_cfg00 = \"hc_" . sha1(md5(CalRoot) . HC_Rando) . "\";\n");
					fwrite($fp, "\$hc_cfg00p = \"helios_" . md5(CalRoot . HC_Rando) . "\";\n");
					fwrite($fp, "?>");
					fclose($fp);
					ob_end_clean();
				} else {
					exit(handleError(0, "Helios Settings Data Missing. You will need to run the Helios Setup at /events/setup"));
				}//end if
				break;
		}//end switch
	}//end rebuildCache()
	/**
	 * Output obfuscated mailto: link. Random variable names decreases likelihood of spam crawlers success.
	 *
	 * @param string $email Email Address for link.
	 * @param string $subject [optional] Email Subject for link.
	 * @return void
	 */
	function cleanEmailLink($email,$subject = ''){
		$var1 = preg_replace('/[0-9]*/', '', md5(rand(0,10845)));
		$var2 = preg_replace('/[0-9]*/', '', md5(rand(10846,20795)));
		$var3 = preg_replace('/[0-9]*/', '', md5(rand(20796,31794)));
		$var4 = preg_replace('/[0-9]*/', '', md5(rand(31794,42847)));
		$eParts = explode("@", $email);
		$eEnds = explode(".", $eParts[1]);
		$eEnds = implode('" + "&#46;" + "',$eEnds);
		echo "\n" . '<script language="JavaScript" type="text/JavaScript">';
		echo "\n" . '//<!--';
		echo "\n" . 'var ' . $var1 . ' = "' . $eParts[0] . '";';
		echo "\n" . 'var ' . $var2 . ' = "' . $eEnds . '";';
		echo "\n" . 'var ' . $var3 . ' = ' . $var1 . ';';
		echo "\n" . 'var ' . $var4 . ' = ' . $var2 . ';';
		echo "\n" . 'document.write(\'<a href="\' + \'mai\' + \'lt\' + \'o:\' + ' . $var3 . ' + \'&#64;\' + ' . $var4;
		echo ($subject != '') ? " + '?subject=" . $subject : " + '";
		echo '" class="eventMain">\' + ' . $var3 . ' + \'&#64;\' + ' . $var4 . ' + \'</a>&nbsp;<br />\');';
		echo "\n" . '//-->';
		echo "\n" . '</script>';
	}//end cleanEmailLink()
	/**
	 * Create formatted address based on language pack localization. Or localisation.
	 *
	 * @param string $add Address Line 1 (Street & Number)
	 * @param string $add2 Address Line 2 (Unit, Suite, Office, etc.)
	 * @param string $city City
	 * @param string $region Region (State, Province, County, etc.)
	 * @param string $postal Postal Code
	 * @param string $country Country
	 * @param binary $addType [optional] Address Format: 0 = US Format, 1 = Europe Format, default:0
	 * @return string
	 */
	function buildAddress($add,$add2,$city,$region,$postal,$country,$addType){
		$address = ($addType == 1) ? '[add][add2][city]<span class="hc_align">,&nbsp;</span>[region]<span class="hc_align">&nbsp;</span>[postal][country]' : '[add][add2][postal]<span class="hc_align">&nbsp;</span>[city][country]';
		
		$address = ($add != '') ? str_replace('[add]','<span class="street-address">' . $add . '</span><br />',$address) : $address;
		$address = ($add2 != '') ? str_replace('[add2]',$add2 . '<br />',$address) : $address;
		$address = ($city != '') ? str_replace('[city]','<span class="locality">' . $city . '</span>',$address) : $address;
		$address = ($region != '') ? str_replace('[region]','<span class="region">' . $region . '</span>',$address) : $address;
		$address = ($postal != '') ? str_replace('[postal]','<span class="postal-code">' . $postal . '</span>',$address) : $address;
		$address = ($country != '') ? str_replace('[country]','<br /><span class="country-name">' . $country . '</span>',$address) : $address;
		$address = preg_replace('/\[+[(a-z|0-9)]*\]+/','',$address);
		
		return $address;
	}//end buildAddress()
	/**
	 * Decode JavaScript escaped UTF8 text.
	 *
	 * @param string $str String to decode.
	 * @return string
	 */
	function utf8_ninjadecode($str) {
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		return html_entity_decode($str,null,'UTF-8');;
	}//end utf8_ninjadecode
	/**
	 * Censored Words Filter
	 *
	 * @param string $text String to filter.
	 * @param array $badWords Array of strings to filter.
	 * @return string
	 */
	function censorWords($text, $badWords){
		return str_replace($badWords, '', $text);
	}//end censorWords()
	/**
	 * Deletes all cache files located in the /events/cache directory.
	 *
	 * @return void
	 */
	function clearCache(){
		foreach(glob('../../events/cache/*.*') as $filename) {
			if($filename != '.' && $filename != '..'){
				unlink($filename);
			}//end if
		}//end foreach
	}//end clearCache()
	/**
	 * Wrapper to send emails using PHPMailer Class. This function should be used for all emails (public calendar & admin console) except newsletter mailings.
	 *
	 * @param string $toName Name of email recipient.
	 * @param string|array $toAddress Email address of email recipient. OR Array of recipients array($name => $address) for multiple recipients.
	 * @param string $subj Subject line of the email.
	 * @param string $msg Message contents of the email.
	 * @param string $fromName Name of email sender.
	 * @param string $fromAddress Email Address of email sender.
	 * @return void
	 */
	function reMail($toName,$toAddress,$subj,$msg,$fromName = '',$fromAddress = ''){
		global $hc_lang_core,$hc_cfg19,$hc_cfg49,$hc_cfg71,$hc_cfg72,$hc_cfg73,$hc_cfg74,$hc_cfg75,$hc_cfg76,$hc_cfg77,$hc_cfg78,$hc_cfg79;

		if($hc_cfg78 == '' || $hc_cfg79 = ''){
			exit($hc_lang_core['NoEmail']);
		}//end if

		include_once('phpmailer/class.phpmailer.php');

		$fromName = ($fromName == '') ? $hc_cfg79 : $fromName;
		$fromAddress = ($fromAddress == '') ? $hc_cfg78 : $fromAddress;
		$mail = new PHPMailer();
		/*	The code following this Comment, used to set header content for email sent by Helios Calendar, may not be modified or removed.
			Alteration, or removal, of this code violates the terms of the Helios Calendar SLA	*/
		$host = gethostbynamel('');
		$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-Version',$hc_cfg49));
		$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-ID',md5($hc_cfg19)));
		$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-IP',$host[0]));
		$mail->AddCustomHeader($mail->HeaderLine('X-Refresh-Report-Abuse','http://www.refreshmy.com/abuse/'));
		$mail->CreateHeader();
		$mail->IsHTML(true);

		if(is_array($toAddress)){
			$mail->SingleToArray = $toAddress;
			foreach($toAddress as $name => $address){
				$mail->AddAddress($address,$name);
			}//end foreach
		} else {
			$mail->AddAddress($toAddress,$toName);
		}//end if

		if($hc_cfg71 == 1){
			$mail->IsSMTP();
			$mail->SMTPKeepAlive = false;
			$mail->SMTPDebug = false;
			$mail->Host = $hc_cfg72;
			$mail->Port = $hc_cfg73;
			if($hc_cfg77 == 1){
				$mail->SMTPAuth = true;
				$mail->Username = $hc_cfg75;
				$mail->Password = base64_decode($hc_cfg76);
			}//end if
			if($hc_cfg74 == 1){
				$mail->SMTPSecure = "tls";
			} elseif($hc_cfg74 == 2){
				$mail->SMTPSecure = "ssl";
			}//end if
		} else {
			$mail->IsMail();
		}//end if

		$mail->Sender = $hc_cfg78;
		$mail->From = $fromAddress;
		$mail->FromName = $fromName;
		$mail->AddReplyTo($fromAddress, $fromName);
		$mail->Subject = $subj;
		$mail->Body = $msg;
		$mail->AltBody = strip_tags($msg);
		
		try{
			$mail->Send();
		} catch (phpmailerException $e) {
			echo $e->errorMessage();
		} catch (Exception $e) {
			echo $e->getMessage();
		}//end try

		if(isset($e) && $e != ''){
			exit('<p>'. $hc_lang_core['EMailFail'] . '</p>');
		}//end if
	}//end reMail()
	/**
	 * Normalizes strings with accented characters. Replaces accented characters with unaccented equivalent.
	 *
	 * @param string $value string to normalize.
	 * @return string
	 */
	function noAccents($value){
		$value = utf8_decode($value);
		return strtr($value,
					utf8_decode("ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ"),
					"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
	}//end noAccents()
	/**
	 * Adds ordinal suffix (primarily used for dates to makeup for strftime's lack thereof).
	 * 
	 * @param string|integer $num The number (or string ending in a number) to which the ordinal will be appended. If last digit or character is not numeric the string is returned unaltered.
	 * @return string
	 */
	function addOrdinal($num) {
		$ord = '';
		$check = substr($num, -1, 1);
		switch($check){
			case "1":
				$ord .= 'st';
				break;
			case "2":
				$ord .= 'nd';
				break;
			case "3":
				$ord .= 'rd';
				break;
			default:
				$ord .= 'th';
				break;
		}//end switch
		return (is_numeric($check)) ? $num . $ord : $num;
	}//end addOrdinal()
?>