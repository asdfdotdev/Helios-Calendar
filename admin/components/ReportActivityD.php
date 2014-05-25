<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	include(HCLANG.'/admin/newsletter.php');
	
	$eID = (isset($_SESSION['ReportDownload'])) ? implode(',',array_filter(explode(',',  utf8_decode($_SESSION['ReportDownload'])),'is_numeric')) : '0';
	
	header('Content-type: application/csv');
	header('Content-Disposition: inline; filename="'.CalName.' Event Report '.SYSDATE.'.csv"');
	
	$resultX = doQuery("SELECT MAX(Views), MAX(Directions), MAX(Downloads), MAX(EmailToFriend), MAX(URLClicks),
						AVG(Views), AVG(Directions), AVG(Downloads), AVG(EmailToFriend), AVG(URLClicks)
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1");
	if(hasRows($resultX)){
		$mViews = cOut(mysql_result($resultX,0,0));
		$mDir = cOut(mysql_result($resultX,0,1));
		$mDwnl = cOut(mysql_result($resultX,0,2));
		$mEmail = cOut(mysql_result($resultX,0,3));
		$mURL =cOut(mysql_result($resultX,0,4));
		$aViews = cOut(round(mysql_result($resultX,0,5), 0));
		$aDir = cOut(round(mysql_result($resultX,0,6), 0));
		$aDwnl = cOut(round(mysql_result($resultX,0,7), 0));
		$aEmail = cOut(round(mysql_result($resultX,0,8), 0));
		$aURL = cOut(round(mysql_result($resultX,0,9), 0));
	}
	
	echo "Event,Views,Directions,Downloads,Email,URL\n";
	echo "Average,".number_format($aViews,0,'.',',').",".number_format($aDir,0,'.',',').",".number_format($aDwnl,0,'.',',').",".number_format($aEmail,0,'.',',').",".number_format($aURL,0,'.',',')."\n";
	echo "Best,".number_format($mViews,0,'.',',').",".number_format($mDir,0,'.',',').",".number_format($mDwnl,0,'.',',').",".number_format($mEmail,0,'.',',').",".number_format($mURL,0,'.',',')."\n";
		
	$result = doQuery("SELECT e.PkID, e.Title, e.Views, e.Directions, e.Downloads, e.EmailToFriend, e.URLClicks
					FROM " . HC_TblPrefix . "events e
					WHERE e.PkID IN(" . cIn($eID) . ") ORDER BY e.PkID");
	
	if(hasRows($result)){
		while($row = mysql_fetch_row($result)){
			echo "(".$row[0].") ".str_replace(',','',$row[1]).",".$row[2].",".$row[3].",".$row[4].",".$row[5].",".$row[6]."\n";
		}
	}
?>
