<?php
$hc_lang_tools = array(

//	Filter Link
'TitleFilter'		=>	'Filter Link Generator',
'InstructFilter'	=>	'Use the form below to generate your filter link. This link will allow you to send traffic to your calendar with the filter pre-set for visitors.<br /><br />To create the link click the categories you want to be <b>active</b> for your filter. The link is generated in the text box. Use this to link to your calendar to activate the filter.',

//	Export Events
'TitleExport'		=>	'Event Data Export',
'InstructExport'	=>	'Use the form below to export event data from your calendar.',

//	Import Events
'TitleImport'		=>	'Event Data Import',
'InstructImport'	=>	'Use the form below to import event data to your calendar in CSV/iCalendar format. Paste the contents of your CSV/iCalendar file into the text area below and click "Import Events".<br /><br />A template CSV file, including a header row with column titles, is available at the link below, use it at your template to import events.<br /><br /><b>Note:</b> If you are importing large numbers of events, it is recomended<br />you break them into smaller groups of <b>500 or fewer</b> to prevent server timeouts.<br /><br />Also it is helpful to upload events by category group type as all events uploaded will be assigned to the selected categories.<br /><br /><a href="javascript:;" onclick="window.location.href=\'' . CalAdminRoot . '/components/ToolImportAction.php?samp=1\';return false;" class="eventMain">Click here to download CSV template file.</a>',

//	Prune Database
'TitlePrune'		=>	'Prune Helios Database',
'InstructPrune'		=>	'Use the button below to prune your Helios database by permanently removing all declined and deleted events.',

//	Update Check
'TitleUpdate'		=>	'Update Check',
'InstructUpdate'	=>	'Helios Calendar will now check to see if a new version is available.<br /><br />You can <a href="http://www.helioscalendar.com/documentation/index.php?title=Changelog" class="main" target="_blank">view the change log here</a>.',

//	Form Elements
'FilterLabel'		=>	'Filter Link Generator',
'Cities'			=>	'Cities:',
'Categories'		=>	'Categories:',
'Link'				=>	'Link:',
'Export'			=>	'Select Your Export Type',
'Export1'			=>	'Text Export',
'Export2'			=>	'CSV Export',
'Delivery'			=>	'Select Delivery Method',
'Delivery1'			=>	'Display Output on Screen',
'Delivery2'			=>	'Download as File',
'Range'				=>	'Events Ocurring From',
'CategoriesLabel'	=>	'In the Following Categories',
'SelectAll'			=>	'Select All Categories',
'DeselectAll'		=>	'Deselect All Categories',
'ImportLabel'		=>	'Select Import Type',
'Import'			=>	'Import Type:',
'Import0'			=>	'CSV/TSV Import',
'Import1'			=>	'iCalendar Import',
'DataLabel'			=>	'Data Field Characters',
'Enclosed'			=>	'Enclosed:',
'Terminated'		=>	'Terminated:',
'EventData'			=>	'Event Data',
'Data'				=>	'Event Data:<br />CSV/iCal',
'Database'			=>	'Your Helios Database has',
'Deleted'			=>	'deleted/declined events',
'NoPrune'			=>	'Unable to retrieve database statistics. Cannot Prune.',
'To'				=>	'to',
'CheckGood'			=>	'You are using the latest version of Helios Calendar.',
'CheckUpdate'		=>	'To download available updates, please visit the <a href="https://www.helioscalendar.com/members/" target="_blank" class="main">Helios Members Site</a>.',
'CheckBroken'		=>	'Helios Update Check Unavailable, please try again later.',
'UserVersion'		=>	'Your Version:',
'CurrentVersion'	=>	'Current Version:',

//	Form Buttons
'ResetLink'			=>	'Reset Link',
'Generate'			=>	'Generate Output',
'ImportButton'		=>	'Import Event Data',
'DoPrune'			=>	'Prune Deleted &amp; Declined Events',

//	Validation
'Valid01'			=>	'Your search could not be completed because of the following reasons:',
'Valid02'			=>	'*Start Date Format is Invalid Date or Format. Required Format:',
'Valid03'			=>	'*Start Date is Required',
'Valid04'			=>	'*End Date Format is Invalid Date or Format. Required Format:',
'Valid05'			=>	'*End Date is Required',
'Valid06'			=>	'*Start Date Cannot Occur After End Date',
'Valid07'			=>	'*Category Selection is Required',
'Valid08'			=>	'Please make the required changes and try again.',
'Valid09'			=>	'Event could not be added for the following reason(s):',
'Valid10'			=>	'*Event Data is Required',
'Valid11'			=>	'*Category Assignment is Required',
'Valid12'			=>	'Please complete the form and try again.',
'Valid13'			=>	'This will permanently remove all deleted and declined events from your Helios database.\n\nAre you sure you wish to proceed.',
'Valid14'			=>	'Ok = YES, Continue and Delete',
'Valid15'			=>	'Cancel = NO, Stop and DO NOT Delete',

//	Feedback
'Feed01'			=>	'Event Import Failed. Your CSV data could not be parsed.<br />Verify Settings and try again.',
'Feed02'			=>	'Event Data Imported Successfully!',
'Feed03'			=>	'Database Pruned Successfully!'
);	?>