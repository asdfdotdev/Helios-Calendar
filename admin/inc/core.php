<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$active = (isset($_GET['com'])) ? cIn(strip_tags($_GET['com'])) : '';
	$com = HCADMIN.'/components/Home.php';

	switch ($active){
		case 'eventadd':
			$com = ($adminEventEdit == 1) ? 'components/EventAdd.php' : $com;
			break;
		case 'eventsearch':
			$com = ($adminEventEdit == 1 || $adminReports == 1) ? 'components/EventSearch.php' : $com;
			break;
		case 'searchresults':
			$com = ($adminEventEdit == 1 || $adminReports == 1) ? 'components/EventSearchResults.php' : $com;
			break;
		case 'eventedit':
			$com = ($adminEventEdit == 1) ? 'components/EventEdit.php' : $com;
			break;
		case 'eventregister':
			$com = ($adminEventEdit == 1) ? 'components/RegisterAdd.php' : $com;
			break;
		case 'eventpending':
			$com = ($adminEventPending == 1) ? 'components/EventPending.php' : $com;
			break;
		case 'eventbillboard':
			$com = ($adminEventEdit == 1) ? 'components/EventBillboard.php' : $com;
			break;
		case 'categorymanage':
			$com = ($adminEventCategory == 1)  ? 'components/CategoryManage.php' : $com;
			break;
		case 'eventorphan':
			$com = ($adminEventEdit == 1) ? 'components/EventOrphan.php' : $com;
			break;
		case 'subedit':
			$com = ($adminUserEdit == 1) ? 'components/MailSubEdit.php' : $com;
			break;
		case 'submngt':
			$com = ($adminUserEdit == 1) ? 'components/MailSubBrowse.php' : $com;
			break;
		case 'adminedit':
			$com = ($adminAdminEdit == 1) ? 'components/AdminEdit.php' : $com;
			break;
		case 'adminbrowse':
			$com = ($adminAdminEdit == 1) ? 'components/AdminBrowse.php' : $com;
			break;
		case 'newsdraft':
			$com = ($adminNewsletter == 1) ? 'components/MailCreate.php' : $com;
			break;
		case 'newsreport':
			$com = ($adminNewsletter == 1) ? 'components/NewsletterReport.php' : $com;
			break;
		case 'newscreate':
			$com = ($adminNewsletter == 1) ? 'components/MailQueue.php' : $com;
			break;
		case 'newsqueue':
			$com = ($adminNewsletter == 1) ? 'components/NewsletterQueue.php' : $com;
			break;
		case 'newssend':
			$com = ($adminNewsletter == 1) ? 'components/NewsletterSend.php' : $com;
			break;
		case 'mailtmplt':
			$com = ($adminNewsletter == 1) ? 'components/MailTemplateEdit.php' : $com;
			break;
		case 'subgrps':
			$com = ($adminNewsletter == 1) ? 'components/MailGroups.php' : $com;
			break;
		case 'seo':
			$com = ($adminSettings == 1) ? 'components/SearchOptimize.php' : $com;
			break;
		case 'generalset':
			$com = ($adminSettings == 1) ? 'components/SettingsGeneral.php' : $com;
			break;
		case 'apiset':
			$com = ($adminSettings == 1) ? 'components/SettingsAPI.php' : $com;
			break;
		case 'api':
			$com = ($adminSettings == 1) ? 'components/SettingsAPIH.php' : $com;
			break;
		case 'themes':
			$com = ($adminSettings == 1) ? 'components/SettingsTheme.php' : $com;
			break;
		case 'reportactivity':
			$com = ($adminReports == 1) ? 'components/ReportActivity.php' : $com;
			break;
		case 'reportpopular':
			$com = ($adminReports == 1) ? 'components/ReportPopular.php' : $com;
			break;
		case 'reportrecent':
			$com = ($adminReports == 1) ? 'components/ReportRecent.php' : $com;
			break;
		case 'reportoverview':
			$com = ($adminReports == 1) ? 'components/ReportOverview.php' : $com;
			break;
		case 'reportfollow':
			$com = ($adminReports == 1) ? 'components/ReportFollowUp.php' : $com;
			break;
		case 'reportdup':
			$com = ($adminReports == 1) ? 'components/ReportDuplicate.php' : $com;
			break;
		case 'reportdupl':
			$com = ($adminReports == 1) ? 'components/ReportDuplicateL.php' : $com;
			break;
		case 'reportfail':
			$com = ($adminReports == 1) ? 'components/ReportFail.php' : $com;
			break;
		case 'export':
			$com = ($adminTools == 1) ? 'components/ToolExport.php' : $com;
			break;
		case 'exporttmplts':
			$com = ($adminTools == 1) ? 'components/TemplatesExport.php' : $com;
			break;
		case 'templateedit':
			$com = ($adminTools == 1) ? 'components/TemplatesEdit.php' : $com;
			break;
		case 'import':
			$com = ($adminTools == 1) ? 'components/ToolImport.php' : $com;
			break;
		case 'filter':
			$com = ($adminTools == 1) ? 'components/ToolFilter.php' : $com;
			break;
		case 'location':
			if($adminLocations == 1)
				$com = (isset($_GET['m']) && $_GET['m'] == 1) ? 'components/LocationMerge.php' : 'components/Location.php';
			break;
		case 'locsearch':
			$com = ($adminLocations == 1) ? 'components/LocationSearch.php' : $com;
			break;
		case 'addlocation':
			$com = ($adminLocations == 1) ? 'components/LocationEdit.php' : $com;
			break;
		case 'digest':
			$com = ($adminPages == 1) ? 'components/Digest.php' : $com;
			break;
		case 'db':
			$com = ($adminTools == 1) ? 'components/ToolDB.php' : $com;
			break;
		case 'user':
			$com = ($adminUserEdit == 1) ? 'components/UserBrowse.php' : $com;
			break;
		case 'useredit':
			$com = ($adminUserEdit == 1) ? 'components/UserEdit.php' : $com;
			break;
		case 'about':
			$com = 'components/About.php';
			break;
	}
	
	if(file_exists($com))
		include_once($com);
	else
		echo '<p>Admin Console File Missing.</p>';
?>