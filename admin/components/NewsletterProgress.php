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
	$isAction = 1;
	include('../includes/include.php');

	if(!isset($_SESSION['AdminLoggedIn'])) {
		header("HTTP/1.1 403 No Direct Access");exit();
	}//end if

	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');
	echo '<link rel="stylesheet" type="text/css" href="' . CalAdminRoot . '/css/admin.css" />';
	echo '<style>html{padding:0px;margin:0px;}</style>';

	$hourOffset = date("G") + ($hc_cfg35);
	$nID = (isset($_GET['n']) && is_numeric($_GET['n'])) ? cIn($_GET['n']) : 0;

	if($nID > 0){
		if($hc_cfg78 == '' || $hc_cfg79 = ''){
			stopError($hc_lang_news['Err03']);
		}//end if
		
		$result = doQuery("SELECT COUNT(ns.SubscriberID), n.SendCount, n.Status, n.StartDate, n.EndDate, n.Subject
						FROM " . HC_TblPrefix . "newsletters n
							LEFT JOIN " . HC_TblPrefix . "newssubscribers ns ON (n.PkID = ns.NewsletterID)
						WHERE n.PkID = '" . $nID . "' AND n.IsActive = 1 GROUP BY n.PkID");
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
			$newsletter = str_replace('<a href="' . CalRoot . '/index.php?com=signup&s=1" target="_blank">' . $hc_lang_news['EditLinkTxt'] . '</a>',$hc_lang_news['EditLinkTxt'],$newsletter);
			$newsletter = str_replace('<a href="http://twitter.com/share?url=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/twitter.png" style="border:0px;" /></a>',
				   '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="' . $hc_cfg63 . '">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>',$newsletter);
			$newsletter = str_replace('<a href="http://www.facebook.com/sharer.php?u=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/facebook.png" style="border:0px;" /></a>',
				   '<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>',$newsletter);
			$newsletter = str_replace('<a href="http://www.google.com/buzz/post?url=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/buzz.png" style="border:0px;" /></a>',
				   '<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count"></a>' . "\n" . '<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>',$newsletter);
			$newsletter = buildPersonal($newsletter,$start,$end,0,'','','','','');
			$newsletter = str_replace('<a','<a rel="nofollow"',$newsletter);
			
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET ArchiveContents = '" . cIn($newsletter,0) . "' WHERE PkID = '" . $nID . "'");

			echo '<div class="progBar" style="background-image: url(../images/progress/go.png);background-position:-500 0;">&nbsp;&nbsp;' . $hc_lang_news['Status0'] . '</div>';
		} else if($prog < 100){
			if(mysql_result($result,0,2) == 2) {
				echo '<div class="progBar" style="background-image: url(../images/progress/pause.png);background-position:'.$position.' 0;">&nbsp;&nbsp;' . $hc_lang_news['Status2'] . ' (' . abs(number_format($prog,0)) . '%)</div>';
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
								LIMIT " . $hc_cfg81);
				if(hasRows($resultS)){
					include_once('../../events/includes/phpmailer/class.phpmailer.php');

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
					
					if($hc_cfg71 == 1){
						$mail->IsSMTP();
						$mail->SMTPKeepAlive = true;
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
					
					$x = 0;
					while($row = mysql_fetch_row($resultS)){
						//	Overkill
						if($x >= $hc_cfg81){
							break;
						}//end if
						++$x;
						
						$fname = cOut($row[1]);
						$lname = cOut($row[2]);
						$email = cOut($row[3]);
						$postal = cOut($row[4]);
						$format = cOut($row[5]);
						$categories = cOut($row[6]);
						$newsletter = buildPersonal($newsletterDefault,$start,$end,$categories,$fname,$lname,$email,$postal);
						doQuery("DELETE FROM " . HC_TblPrefix . "newssubscribers WHERE NewsletterID = '" . $nID . "' AND SubscriberID = '" . cIn($row[0]) . "'");
						
						if($row[5] == 1){
							$newsletter = str_replace('index.php?com=detail&','m/details.php?',$newsletter);
						}//end if
						
						$mail->AddAddress($email,trim($fname . ' ' . $lname));
						$mail->Sender = $hc_cfg78;
						$mail->From = $hc_cfg78;
						$mail->FromName = $hc_cfg79;
						$mail->AddReplyTo($hc_cfg78, $hc_cfg79);
						$mail->Subject = $subject;
						$mail->Body = $newsletter;
						$mail->AltBody = $hc_lang_news['ArchiveLinkTxt'] . ' @ ' . $archive;
						
						try{
							$mail->Send();
						} catch (phpmailerException $e) {
							// Uncomment to Troubleshoot in Detail
							// echo $e->errorMessage();
							$mError = 1;
							break;
						} catch (Exception $e) {
							// Uncomment to Troubleshoot in Detail
							// echo $e->getMessage();
							$mError = 1;
							break;
						}//end try

						$mail->ClearAddresses();
						$mail->ClearAttachments();
					}//end while
					if($hc_cfg71 == 1){$mail->SmtpClose();}
				} else {
					stopError($hc_lang_news['Err02']);
				}//end if
				
				if(!isset($mError)){
					$left = $left - $hc_cfg81;
					$prog = ($left > 0) ? 100 - ($left / $total) * 100 : 100;
					$position = -500 + number_format(($prog * 5),0);
					
					echo '<meta http-equiv="REFRESH" content="' . $hc_cfg82 . ';url="NewsletterProgress.php?n=' . $nID . '">';
					echo '<div class="progBar" style="background-image: url(../images/progress/go.png);background-position:'.$position.' 0;">&nbsp;&nbsp;' . abs(number_format($prog,0)) . '% ' . $hc_lang_news['Complete'] . '</div>';
				} else {
					stopError($hc_lang_news['Err04']);
				}//end if
			}//end if
		} else {
			if(file_exists('../../events/cache/news' . date("ymd") . '_' . $nID . '.txt')){
				unlink('../../events/cache/news' . date("ymd") . '_' . $nID . '.txt');
			}//end if
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Status = 3 WHERE PkID = '" . $nID . "'");
			echo '<div class="progBar" style="background-image: url(../images/progress/go.png);background-position:'.$position.' 0;">&nbsp;&nbsp;100% ' . $hc_lang_news['Complete'] . '</div>';?>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
				alert('<?php echo $hc_lang_news['CompleteNotice'];?>');
			//-->
			</script>
<?php	}//end if
	}//end if
	/**
	 * Builds the newsletter template replacing personal variables with their content.
	 * 
	 * @param string $tmplt Universal template content.
	 * @param date $start Start Date for event list.
	 * @param date $end End Date for event lists.
	 * @param string $categories Comma separated string of category ids event list.
	 * @param int $format Event Link Format
	 * @param string $fname Subscriber's first name.
	 * @param string $lname Subscriber's last name.
	 * @param string $email Subscriber's email address.
	 * @param string $postal Subscribers postal code.
	 * @return string
	 */
	function buildPersonal($template,$start,$end,$categories,$fname,$lname,$email,$postal){
		global $hc_lang_news, $hourOffset, $hc_cfg14, $hc_cfg23;

		if(stristr($template,'[firstname]')){
			$template = str_replace('[firstname]',$fname,$template);
		}//end if
		if(stristr($template,'[lastname]')){
			$template = str_replace('[lastname]',$lname,$template);
		}//end if
		if(stristr($template,'[email]')){
			$template = str_replace('[email]',$email,$template);
		}//end if
		if(stristr($template,'[postal]')){
			$template = str_replace('[postal]',$postal,$template);
		}//end if
		
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
						$events .= '<div style="clear:both;font-weight:bold;padding-top:15px;">' . stampToDate($row[2], $hc_cfg14) . '</div>';
					}//end if
					$events .= ($cnt % 2 == 1) ? '<div style="background:#EFEFEF;clear:both;">&nbsp;' : '<div style="clear:both;">&nbsp;';
					
					if($row[5] == 0){
						if(strlen($row[4]) > 0){
							$events .= '<div style="width:25%;float:left;">' . strftime($hc_cfg23, strtotime($row[3])) . ' - ' . strftime($hc_cfg23, strtotime($row[4])) . '</div>';
						} else {
							$events .= '<div style="width:25%;float:left;">' . strftime($hc_cfg23, strtotime($row[3])) . '</div>';
						}//end if
					} elseif($row[5] == 1){
						$events .= '<div style="width:25%;float:left;"><i>' . $hc_lang_news['AllDay'] . '</i></div>';
					} elseif($row[5] == 2){
						$events .= '<div style="width:25%;float:left;"><i>' . $hc_lang_news['TBA'] . '</i></div>';
					}//end if
					
					$events .= '<div style="width:70%;float:left;"><a href="' . CalRoot . '/index.php?com=detail&eID=' . $row[0] . '" target="_blank">' . $row[1] . '</a></div>';
					$events .= '</div>';
					++$cnt;
				}//end if
			}//end if
			$template = str_replace('[events]',$events,$template);
		}//end if

		return $template;
	}//end buildPersonal()
	/**
	 * Builds newletter template using the assigned template replacing universal variables with their content.
	 *
	 * @param int $nID Newsletter ID
	 * @return string
	 */
	function buildUniversal($nID){
		global $hc_lang_news, $hourOffset, $hc_cfg23, $hc_cfg63;
		$listMax = 5;
		
		$tmplCache = '../../events/cache/news' . date("ymd") . '_' . $nID . '.txt';
		$today = date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
		if(!file_exists($tmplCache)){
			foreach(glob('../../events/cache/news*_' . $nID . '.txt') as $filename) {
				unlink($filename);
			}//end foreach

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
			}//end if
			
			$template = str_replace('[message]', $message, $template);
			
			if(stristr($template,'[billboard]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $today . "' AND IsBillboard = 1 ORDER BY IsBillboard DESC, StartDate, StartTime, Title LIMIT " . ($listMax*2);
				$template = str_replace('[billboard]', getEventList($query,$listMax),$template);
			}//end if
			if(stristr($template,'[popular]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $today . "' ORDER BY Views DESC, StartDate, StartTime, Title LIMIT " . ($listMax*2);
				$template = str_replace('[popular]', getEventList($query,$listMax),$template);
			}//end if
			if(stristr($template,'[newest]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $today . "' ORDER BY PublishDate DESC, StartDate LIMIT " . ($listMax*2);
				$template = str_replace('[newest]', getEventList($query,$listMax),$template);
			}//end if
			if(stristr($template,'[today]')){
				$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $today . "' ORDER BY StartDate, StartTime, Title LIMIT " . ($listMax*2);
				$template = str_replace('[today]', getEventList($query,$listMax),$template);
			}//end if
			if(stristr($template,'[twitter]')){
				$template = str_replace('[twitter]','<a href="http://twitter.com/share?url=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/twitter.png" style="border:0px;" /></a>',$template);
			}//end if
			if(stristr($template,'[facebook]')){
				$template = str_replace('[facebook]','<a href="http://www.facebook.com/sharer.php?u=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/facebook.png" style="border:0px;" /></a>',$template);
			}//end if
			if(stristr($template,'[buzz]')){
				$template = str_replace('[buzz]','<a href="http://www.google.com/buzz/post?url=' . urlencode($archive) . '" target="_blank"><img src="' . CalRoot . '/newsletter/images/buzz.png" style="border:0px;" /></a>',$template);
			}//end if
			if(stristr($template,'[follow]')){
				$follow = ($hc_cfg63 != '') ? '<a href="http://www.twitter.com/'.$hc_cfg63.'" target="_blank"><img src="' . CalRoot . '/newsletter/images/follow_me.png" style="border:0px;" /></a>' : '';
				$template = str_replace('[follow]',$follow,$template);
			}//end if
			if(stristr($template,'[calendarurl]')){
				$template = str_replace('[calendarurl]','<a href="' . CalRoot . '/" target="_blank">' . CalRoot . '/</a>',$template);
			}//end if
			if(stristr($template,'[editcancel]')){
				$template = str_replace('[editcancel]','<a href="' . CalRoot . '/index.php?com=signup&s=1" target="_blank">' . $hc_lang_news['EditLinkTxt'] . '</a>',$template);
			}//end if
			if(stristr($template,'[archive]')){
				$template = ($doArchive == 1) ? str_replace('[archive]','<a href="' . $archive . '" target="_blank">' . $hc_lang_news['ArchiveLinkTxt'] . '</a>',$template) : str_replace('[archive]','',$template);
			}//end if
			if(stristr($template,'[event-count]')){
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $today . "'");
				$eCnt = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : 0;
				$template = str_replace('[event-count]',$eCnt,$template);
			}//end if
			if(stristr($template,'[location-count]')){
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1");
				$lCnt = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : 0;
				$template = str_replace('[location-count]',$lCnt,$template);
			}//end if
			if(stristr($template,'[track]')){
				$template = str_replace('[track]','<img src="' . CalRoot . '/newsletter/a.php?a=' . md5($nID) . '" width="1" height="1" />',$template);
			}//end if
			ob_flush();
			ob_start();
			$fp = fopen($tmplCache, 'w');
			echo $template;
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}//end if
		
		return includeToString(realpath($tmplCache));
	}//end buildUniversal()
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
		}//end if
		return false;
	}//end if
	/**
	 * Builds list of events for the template based on the passed query.
	 * @param string $qry Columns: PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime
	 * @return string
	 */
	function getEventList($qry, $listMax){
		global $hc_lang_news, $hc_cfg14, $hc_cfg23;
		
		$replace = $curDate = '';
		$hide = array();
		$cnt = 0;
		$result = doQuery($qry);
		$str = '<ul style="list-style:none;padding:0px;">';
		while($row = mysql_fetch_row($result)){
			if($cnt >= $listMax){break;}
			if($row[5] == '' || !in_array($row[5], $hide)){
				if($curDate != $row[2]){
					$curDate = cOut($row[2]);
					$str .= '<li>' . stampToDate($row[2], $hc_cfg14) . '</li>';
				}//end if
				
				$str .= '<li style="padding-left:15px;"><a href="' . CalRoot . '/index.php?com=detail&eID=' . $row[0] . '" target="_blank">' . $row[1] . '</a>';
				
				if($row[6] == 0){
					$str .= ' - ' . strftime($hc_cfg23, strtotime($row[3])) . '</li>';
				} elseif($row[6] == 1) {
					$str .= ' - <i>' . $hc_lang_news['AllDay'] . '</i></li>';
				} elseif($row[6] == 2) {
					$str .= ' - <i>' . $hc_lang_news['TBA'] . '</i></li>';
				}//end if
			}//end if
			if($row[5] != '' && (!in_array($row[5], $hide))){$hide[] = $row[5];}
			++$cnt;
		}//end if
		$str .= '</ul>';
		
		return $str;
	}//end getEventList()
	/**
	 * Kills the sending of newsletters and outputs a progress bar with the provided error notice.
	 * @param string $errMsg The error message to display.
	 * @return void
	 */
	function stopError($errMsg){
		global $hc_lang_news;
		echo '<div class="progBar" style="background-image: url(../images/progress/go.png);background-position:-500 0;color:#DC143C;">&nbsp;&nbsp;' . $hc_lang_news['Error'] . ' ' . $errMsg . '</div>';
		exit();
	}//end if
?>