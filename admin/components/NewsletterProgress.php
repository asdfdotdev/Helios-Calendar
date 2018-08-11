<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	define('SAFE_REFER',true);
	
	admin_logged_in();
	action_headers();
	
	include(HCLANG.'/admin/newsletter.php');
	
	echo '
	<link rel="stylesheet" type="text/css" href="'.AdminRoot.'/css/admin.css" />
	<style>
		html {padding:0px;margin:0px;}
		body {background:none;}
	</style>
	<script>
	//!<--
	if(self == top || parent != top)
		document.location.href = "'.AdminRoot.'/signout.php";
	//-->
	</script>';

	$nID = (isset($_GET['n']) && is_numeric($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : 0;

	if($nID > 0){
		if($hc_cfg[78] == '' || $hc_cfg[79] = '')
			stopError($hc_lang_news['Err03']);
		
		$result = doQuery("SELECT COUNT(ns.SubscriberID), n.SendCount, n.Status, n.StartDate, n.EndDate, n.Subject
						FROM " . HC_TblPrefix . "newsletters n
							LEFT JOIN " . HC_TblPrefix . "newssubscribers ns ON (n.PkID = ns.NewsletterID)
						WHERE n.PkID = '" . $nID . "' AND n.IsActive = 1
						GROUP BY n.PkID, n.SendCount, n.Status, n.StartDate, n.EndDate, n.Subject");
		$left = mysql_result($result,0,0);
		$total = mysql_result($result,0,1);
		$start = mysql_result($result,0,3);
		$end = mysql_result($result,0,4);
		$archive = CalRoot . '/newsletter/index.php?n=' . md5($nID);
		$subject = mysql_result($result,0,5);
		$prog = ($left > 0) ? 100 - ($left / $total) * 100 : 100;
		$position = -500 + number_format(($prog * 5),0);

		if(mysql_result($result,0,2) == 0){
			$newsletter = buildUniversal($nID);
			$newsletter = str_replace('<img src="' . CalRoot . '/newsletter/a.php?a=' . md5($nID) . '" width="1" height="1" />','',$newsletter);
			$newsletter = str_replace('<a href="' . $archive . '" target="_blank">' . $hc_lang_news['ArchiveLinkTxt'] . '</a>',$hc_lang_news['ArchiveLinkTxt'],$newsletter);
			$newsletter = str_replace('<a href="' . CalRoot . '/index.php?com=edit" target="_blank">' . $hc_lang_news['EditLinkTxt'] . '</a>',$hc_lang_news['EditLinkTxt'],$newsletter);
			$newsletter = str_replace('<a href="http://twitter.com/share?url=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/twitter.png" style="border:0px;" /></a>',
				   '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="' . $hc_cfg[63] . '">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>',$newsletter);
			$newsletter = str_replace('<a href="http://www.facebook.com/sharer.php?u=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/facebook.png" style="border:0px;" /></a>',
				   '<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>',$newsletter);		
			$newsletter = buildPersonal($newsletter,$start,$end,0,'','','','','');
			$newsletter = str_replace('<a','<a rel="nofollow"',$newsletter);
			
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET ArchiveContents = '" . cIn($newsletter,0) . "' WHERE PkID = '" . $nID . "'");

			echo '<div class="progBar" style="background-image: url(../img/progress/go.png);background-position:-500px 0px;">&nbsp;&nbsp;' . $hc_lang_news['Status0'] . '</div>';
		} else if($prog < 100){
			if(mysql_result($result,0,2) == 2) {
				echo '<div class="progBar" style="background-image: url(../img/progress/pause.png);background-position:'.$position.'px 0px;">&nbsp;&nbsp;' . $hc_lang_news['Status2'] . ' (' . abs(number_format($prog,0)) . '%)</div>';
			} else {
				$newsletterDefault = buildUniversal($nID);
				
				$resultS = doQuery("SELECT s.PkID, s.FirstName, s.LastName, s.Email, s.Zip, s.`Format`,
								(SELECT GROUP_CONCAT(c.PkID)
									FROM " . HC_TblPrefix . "subscriberscategories sc
									LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = sc.CategoryID)
									WHERE c.IsActive = 1 AND sc.UserID = s.PkID
								) as Categories
								FROM " . HC_TblPrefix . "newssubscribers ns
									LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = ns.SubscriberID)
								WHERE ns.NewsletterID = '" . $nID . "'
								LIMIT " . $hc_cfg[81]);
				if(hasRows($resultS)){
					include_once(HCPATH.HCINC.'/phpmailer/class.phpmailer.php');

					$mail = new PHPMailer();
					$host = gethostbynamel('');
					$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-Version',$hc_cfg[49]));
					$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-ID',md5($hc_cfg[19])));
					$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-IP',$host[0]));
					$mail->CreateHeader();
					$mail->IsHTML(true);
					
					if($hc_cfg[71] == 1){
						$mail->IsSMTP();
						$mail->SMTPKeepAlive = true;
						$mail->SMTPDebug = false;
						$mail->Host = $hc_cfg[72];
						$mail->Port = $hc_cfg[73];
						if($hc_cfg[77] == 1){
							$mail->SMTPAuth = true;
							$mail->Username = $hc_cfg[75];
							$mail->Password = base64_decode($hc_cfg[76]);
						}
						if($hc_cfg[74] == 1){
							$mail->SMTPSecure = "tls";
						} elseif($hc_cfg[74] == 2){
							$mail->SMTPSecure = "ssl";
						}
					} else {
						$mail->IsMail();
					}
					
					$x = 0;
					while($row = mysql_fetch_row($resultS)){
						//	Overkill
						if($x >= $hc_cfg[81]){
							break;
						}
						++$x;
						
						$fname = cOut($row[1]);
						$lname = cOut($row[2]);
						$email = cOut($row[3]);
						$postal = cOut($row[4]);
						$format = cOut($row[5]);
						$categories = cOut($row[6]);
						$newsletter = buildPersonal($newsletterDefault,$start,$end,$categories,$fname,$lname,$email,$postal,$format);
						doQuery("DELETE FROM " . HC_TblPrefix . "newssubscribers WHERE NewsletterID = '" . $nID . "' AND SubscriberID = '" . cIn(strip_tags($row[0])) . "'");
						
						$mail->AddAddress($email,trim($fname . ' ' . $lname));
						$mail->Sender = $hc_cfg[78];
						$mail->From = $hc_cfg[78];
						$mail->FromName = $hc_cfg[79];
						$mail->AddReplyTo($hc_cfg[78], $hc_cfg[79]);
						$mail->Subject = $subject;
						$mail->Body = $newsletter;
						$mail->AltBody = $hc_lang_news['ArchiveLinkTxt'] . ' @ ' . $archive;
						
						try{
							$mail->Send();
						} catch (phpmailerException $e) {
							// Uncomment following to Troubleshoot in Detail
							// echo $e->errorMessage();
							$mError = 1;
							break;
						} catch (Exception $e) {
							// Uncomment following to Troubleshoot in Detail
							// echo $e->getMessage();
							$mError = 1;
							break;
						}

						$mail->ClearAddresses();
						$mail->ClearAttachments();
					}
					if($hc_cfg[71] == 1){$mail->SmtpClose();}
				} else {
					stopError($hc_lang_news['Err02']);
				}
				
				if(!isset($mError)){
					$left = $left - $hc_cfg[81];
					$prog = ($left > 0) ? 100 - ($left / $total) * 100 : 100;
					$position = -500 + number_format(($prog * 5),0);
					
					echo '
			<meta http-equiv="REFRESH" content="' . $hc_cfg[82] . ';url="NewsletterProgress.php?n='.$nID.'">
			<div class="progBar" style="background-image: url(../img/progress/go.png);background-position:'.$position.'px 0px;">&nbsp;&nbsp;'.abs(number_format($prog,0)).'% '.$hc_lang_news['Complete'].'</div>';
				} else {
					stopError($hc_lang_news['Err04']);
				}
			}
		} else {
			if(file_exists(HCPATH.'/cache/news' . date("ymd") . '_' . $nID . '.txt')){
				unlink(HCPATH.'/cache/news' . date("ymd") . '_' . $nID . '.txt');
			}
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Status = 3 WHERE PkID = '" . $nID . "'");
			echo '
			<div class="progBar" style="background-image: url(../img/progress/go.png);background-position:'.$position.'px 0px;">&nbsp;&nbsp;100% '.$hc_lang_news['Complete'].'</div>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
				alert("'.$hc_lang_news['CompleteNotice'].'");
			//-->
			</script>';
		}
	}
	/**
	 * Builds the newsletter template replacing personal variables with their content.
	 * 
	 * @param string $template Universal template content.
	 * @param date $start Start Date for event list.
	 * @param date $end End Date for event lists.
	 * @param string $categories Comma separated string of category ids event list.
	 * @param string $fname Subscriber's first name.
	 * @param string $lname Subscriber's last name.
	 * @param string $email Subscriber's email address.
	 * @param string $postal Subscribers postal code.
 	 * @param integer $format Event Link Format
	 * @return string
	 */
	function buildPersonal($template,$start,$end,$categories,$fname,$lname,$email,$postal,$format){
		global $hc_lang_news, $hc_cfg;
		
		$formatLink = '';
		if($format < 2)
			$formatLink = ($format == 1) ? '&theme='.$hc_cfg[84] : '&theme='.$hc_cfg[83];
		if(stristr($template,'[firstname]'))
			$template = str_replace('[firstname]',$fname,$template);
		if(stristr($template,'[lastname]'))
			$template = str_replace('[lastname]',$lname,$template);
		if(stristr($template,'[email]'))
			$template = str_replace('[email]',$email,$template);
		if(stristr($template,'[postal]'))
			$template = str_replace('[postal]',$postal,$template);
		
		if(stristr($template,'[events]')){
			$events = '';
			$now = date("Y-m-d");
			$start = ($now > $start) ? $now : $start;
			$query = "SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
						LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
					WHERE e.StartDate BETWEEN '" . cIn($start) . "' AND '" . cIn($end) . "' AND e.IsActive = 1 AND e.IsApproved = 1 ";
			$query .= ($categories != '') ? " AND ec.CategoryID in (" . $categories . ") " : '';
			$query .= "ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
			
			$resultP = doQuery($query);
			if(hasRows($resultP)){
				$curDate = '';
				while($row = mysql_fetch_row($resultP)){
					if($curDate != $row[2]){
						$cnt = 0;
						$curDate = $row[2];
						$events .= '<div style="clear:both;font-weight:bold;padding-top:15px;">' . stampToDate($row[2], $hc_cfg[14]) . '</div>';
					}
					$events .= ($cnt % 2 == 1) ? '<div style="background:#EFEFEF;clear:both;">&nbsp;' : '<div style="clear:both;">&nbsp;';
					
					if($row[5] == 0){
						if(strlen($row[4]) > 0){
							$events .= '<div style="width:25%;float:left;">' . strftime($hc_cfg[23], strtotime($row[3])) . ' - ' . strftime($hc_cfg[23], strtotime($row[4])) . '</div>';
						} else {
							$events .= '<div style="width:25%;float:left;">' . strftime($hc_cfg[23], strtotime($row[3])) . '</div>';
						}
					} elseif($row[5] == 1){
						$events .= '<div style="width:25%;float:left;"><i>' . $hc_lang_news['AllDay'] . '</i></div>';
					} elseif($row[5] == 2){
						$events .= '<div style="width:25%;float:left;"><i>' . $hc_lang_news['TBA'] . '</i></div>';
					}
					
					$events .= '<div style="width:70%;float:left;"><a href="' . CalRoot . '/index.php?eID='.$row[0].$formatLink.'" target="_blank">' . $row[1] . '</a></div>';
					$events .= '</div>';
					++$cnt;
				}
			}
			$template = str_replace('[events]',$events,$template);
		}

		return $template;
	}
	/**
	 * Builds newletter template using the assigned template replacing universal variables with their content.
	 *
	 * @param int $nID Newsletter ID
	 * @return string
	 */
	function buildUniversal($nID){
		global $hc_lang_news, $hc_cfg;
		
		$tmplCache = HCPATH.'/cache/news' . date("ymd") . '_' . $nID . '.txt';
		if(!file_exists($tmplCache)){
			foreach(glob(HCPATH.'/cache/news*_' . $nID . '.txt') as $filename) {
				unlink($filename);
			}

			$result = doQuery("SELECT tn.TemplateSource, n.Message, n.IsArchive
							FROM " . HC_TblPrefix . "newsletters n
								LEFT JOIN " . HC_TblPrefix . "templatesnews tn ON (n.TemplateID = tn.PkID)
							WHERE n.PkID = '" . $nID . "' AND n.IsActive = 1 AND tn.IsActive = 1");
			$template = $message = $archive = '';
			$doArchive = 0;
			if(hasRows($result)){
				$template = cOut(mysql_result($result,0,0));
				$message = cOut(mysql_result($result,0,1));
				$doArchive = cOut(mysql_result($result,0,2));
				$archive = CalRoot . '/newsletter/index.php?n=' . md5($nID);
			} else {
				stopError($hc_lang_news['Err01']);
			}
			
			$template = str_replace('[message]', $message, $template);
			
			if(stristr($template,'[billboard]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . SYSDATE . "' AND IsBillboard = 1 ORDER BY IsBillboard DESC, StartDate, StartTime, Title LIMIT " . $hc_cfg[12];
				$template = str_replace('[billboard]', getEventList($query),$template);
			}
			if(stristr($template,'[popular]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime, (Views / (DATEDIFF('".SYSDATE."', PublishDate)+1)) as Ave FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . SYSDATE . "' ORDER BY Ave DESC, StartDate, StartTime, Title LIMIT " . $hc_cfg[10];
				$template = str_replace('[popular]', getEventList($query),$template);
			}
			if(stristr($template,'[newest]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . SYSDATE . "' ORDER BY PublishDate DESC, StartDate LIMIT " . $hc_cfg[66];
				$template = str_replace('[newest]', getEventList($query),$template);
			}
			if(stristr($template,'[updated]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . SYSDATE . "' ORDER BY LastMod DESC, StartDate LIMIT " . $hc_cfg[66];
				$template = str_replace('[updated]', getEventList($query),$template);
			}
			if(stristr($template,'[today]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . SYSDATE . "' ORDER BY StartDate, StartTime, Title LIMIT " . $hc_cfg[12];
				$template = str_replace('[today]', getEventList($query),$template);
			}
			if(stristr($template,'[twitter]')){
				$template = str_replace('[twitter]','<a href="http://twitter.com/share?url=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/twitter.png" style="border:0px;" /></a>',$template);
			}
			if(stristr($template,'[facebook]')){
				$template = str_replace('[facebook]','<a href="http://www.facebook.com/sharer.php?u=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/facebook.png" style="border:0px;" /></a>',$template);
			}
			if(stristr($template,'[follow]')){
				$follow = ($hc_cfg[63] != '') ? '<a href="http://www.twitter.com/'.$hc_cfg[63].'" target="_blank"><img src="' . CalRoot . '/newsletter/images/follow_me.png" style="border:0px;" /></a>' : '';
				$template = str_replace('[follow]',$follow,$template);
			}
			if(stristr($template,'[calendarurl]')){
				$template = str_replace('[calendarurl]','<a href="' . CalRoot . '/" target="_blank">' . CalRoot . '/</a>',$template);
			}
			if(stristr($template,'[editcancel]')){
				$template = str_replace('[editcancel]','<a href="' . CalRoot . '/index.php?com=edit" target="_blank">' . $hc_lang_news['EditLinkTxt'] . '</a>',$template);
			}
			if(stristr($template,'[archive]')){
				$template = ($doArchive == 1) ? str_replace('[archive]','<a href="' . $archive . '" target="_blank">' . $hc_lang_news['ArchiveLinkTxt'] . '</a>',$template) : str_replace('[archive]','',$template);
			}
			if(stristr($template,'[event-count]')){
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "'");
				$eCnt = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : 0;
				$template = str_replace('[event-count]',$eCnt,$template);
			}
			if(stristr($template,'[location-count]')){
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1");
				$lCnt = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : 0;
				$template = str_replace('[location-count]',$lCnt,$template);
			}
			if(stristr($template,'[track]')){
				$template = str_replace('[track]','<img src="' . CalRoot . '/newsletter/a.php?a=' . md5($nID) . '" width="1" height="1" />',$template);
			}
			ob_flush();
			ob_start();
			$fp = fopen($tmplCache, 'w');
			echo $template;
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		
		return includeToString(realpath($tmplCache));
	}
	/**
	 * Load the contents of a text file (in this case the universal template cache) into a string variable.
	 * @param string $filename Filesystem path to the file. Use realpath() before passing this parameter.
	 * @return string
	 */
	function includeToString($filename){
		if(is_file($filename)) {
			ob_flush();
			ob_start();
			include $filename;
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		return false;
	}
	/**
	 * Builds list of events for the template based on the passed query.
	 * @param string $qry Columns: PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime
	 * @return string
	 */
	function getEventList($qry){
		global $hc_lang_news, $hc_cfg;
		
		$replace = $curDate = '';
		$hide = array();
		$cnt = 0;
		$result = doQuery($qry);
		$str = '<ul style="list-style:none;padding:0px;">';
		while($row = mysql_fetch_row($result)){
			if($row[5] == '' || !in_array($row[5], $hide)){
				if($curDate != $row[2]){
					$curDate = cOut($row[2]);
					$str .= '<li>' . stampToDate($row[2], $hc_cfg[14]) . '</li>';
				}
				
				$str .= '<li style="padding-left:15px;"><a href="' . CalRoot . '/index.php?eID=' . $row[0] . '" target="_blank">' . $row[1] . '</a>';
				
				if($row[6] == 0){
					$str .= ' - ' . strftime($hc_cfg[23], strtotime($row[3])) . '</li>';
				} elseif($row[6] == 1) {
					$str .= ' - <i>' . $hc_lang_news['AllDay'] . '</i></li>';
				} elseif($row[6] == 2) {
					$str .= ' - <i>' . $hc_lang_news['TBA'] . '</i></li>';
				}
			}
			if($row[5] != '' && (!in_array($row[5], $hide))){$hide[] = $row[5];}
			++$cnt;
		}
		$str .= '</ul>';
		
		return $str;
	}
	/**
	 * Kills the sending of newsletters and outputs a progress bar with the provided error notice.
	 * @param string $errMsg The error message to display.
	 * @return void
	 */
	function stopError($errMsg){
		global $hc_lang_news;
		echo '<div class="progBar" style="background-image: url(../img/progress/go.png);background-position:-500px 0px;color:#DC143C;">&nbsp;&nbsp;' . $hc_lang_news['Error'] . ' ' . $errMsg . '</div>';
		exit();
	}
?>