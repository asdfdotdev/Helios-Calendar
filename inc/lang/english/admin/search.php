<?php
$hc_lang_search = array(

//	Edit Events
'TitleEdit'			=>	'Edit Event Search',
'InstructEdit'		=>	'Please use the form below to search for the event(s) you wish to edit.',
'TitleEditR'		=>	'Event Edit Search Results',
'InstructEditR'		=>	'Select an event from the list below to edit. To edit a group of events click the checkbox to the right of the event listing than click the "Edit Select Events As A Group" button below.<br /><br /><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt="" /> = Edit Event<br /><img src="' . AdminRoot . '/img/icons/edit_new.png" width="16" height="16" alt="" /> = Edit Event In New Window<br /><img src="' . AdminRoot . '/img/icons/recycle.png" width="16" height="16" alt="" /> = Recycle Event',
'InstructEditRS'	=>	'The list below contains only event series (recurring events). The results below are limited to the earliest active occurence of the event series.<br /><br /><img src="' . AdminRoot . '/img/icons/edit_group.png" width="16" height="16" alt="" /> = Edit Entire Event Series (Includes Past Events)<br /><img src="' . AdminRoot . '/img/icons/view_series.png" width="16" height="16" alt="" /> = View Entire Events Series (Includes Past Events)',

//	Delete Events
'TitleDelete'		=>	'Delete Event Search',
'InstructDelete'	=>	'Please use the form below to search for the event(s) you wish to delete.',
'TitleDeleteR'		=>	'Delete Events Search Results',
'InstructDeleteR'	=>	'Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the "Delete Selected" button below to delete the events.<br /><br /><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt="" /> = Edit Event<br /><img src="' . AdminRoot . '/img/icons/edit_new.png" width="16" height="16" alt="" /> = Edit Event In New Window<br /><img src="' . AdminRoot . '/img/icons/recycle.png" width="16" height="16" alt="" /> = Recycle Event',

//	Create Series
'TitleSeries'		=>	'Create Series Search',
'InstructSeries'	=>	'Please use the form below to search for the events you wish to include in your series.',
'TitleSeriesR'		=>	'Create Series Search Results',
'InstructSeriesR'	=>	'Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the "Create Event Series" button below to create the series.',

//	Event Report
'TitleReport'		=>	'Event Report Search',
'InstructReport'	=>	'Please use the form below to search the events for which you wish to generate a report.',
'TitleReportR'		=>	'Event Report Search Results',
'InstructReportR'	=>	'Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the "Generate Report" button below.',

//	Form Elements
'DateTitle'			=>	'Search Dates',
'DateRange'			=>	'Date Range:',
'KeywordTitle'		=>	'Search Keywords',
'Keywords'			=>	'Keyword(s):',
'LinkLocation'		=>	'Location',
'LinkCity'			=>	'City',
'LinkPostal'		=>	'Zip Code',
'Location'			=>	'Location:',
'Location0'			=>	'All Locations',
'City'				=>	'City:',
'City0'				=>	'All Cities',
'Postal'			=>	'Zip Code:',
'Postal0'			=>	'All Zip Codes',
'CategoryTitle'		=>	'Categories',
'Categories'		=>	'Categories:',
'SeriesTitle'		=>	'Search Event Series',
'SeriesOnly'		=>	'Limit Results to Event Series Only',
'SelectAll'			=>	'Select All',
'DeselectAll'		=>	'Deselect All',
'None01'			=>	'<b>You must have at least 2 events to create a series.</b><br /><a href="index.php?com=eventsearch&sID=3">Click here to search again.</a>',
'None02'			=>	'There are no events that meet that search criteria.',
'To'				=>	'to',
'AllDay'			=>	'All Day Event',
'TBD'				=>	'TBA',
'RegSelect'			=>	'All States',
'UserTitle'			=>	'Users',
'UserSearch'		=>	'User Search:',
'UserNotice'		=>	'Enter Username or Email Address, after 4 or more characters results will appear.',
'ClearSearch'		=>	'Clear Search',

//	Form Buttons
'Search'			=>	'Begin Search',
'EditGroup'			=>	'Edit Selected Events As A Group',
'DeleteGroup'		=>	'Delete Selected Events',
'CreateSeries'		=>	'Create Event Series',
'GenerateReport'	=>	'Generate Event Report',

//	Location Search
'NoLocation'		=>	'There are no locations available with that name.',
'CheckLocInst'		=>	'Enter Location Name, after 4 or more characters results will appear.',
'ResetSearch'		=>	'Reset Location Search',

//	Tooltips
'Tip01'				=>	'Limit search results to only multiple date events (Event Series). Search results will show only the earliest active occurrence of the event series.',

//	Validation
'Valid01'			=>	'Your search could not be completed because of the following reasons:',
'Valid02'			=>	'Start Date Format is Invalid Date or Format. Required Format:',
'Valid03'			=>	'Start Date is Required',
'Valid04'			=>	'End Date Format is Invalid Date or Format. Required Format:',
'Valid05'			=>	'End Date is Required',
'Valid06'			=>	'Start Date Cannot Occur After End Date',
'Valid07'			=>	'Category Selection is Required',
'Valid08'			=>	'Please make the required changes and submit your search again.',
'Valid09'			=>	'No events selected.\\nPlease select at least one event and try again.',
'Valid10'			=>	'Editing events as a group will begin with the first selected event. All others will be updated to that events details.\\nAre you sure you want to edit the selected events as a group?',
'Valid11'			=>	'Ok = YES Edit the Selected Events as a Group',
'Valid12'			=>	'Cancel = NO Do Not Continue',
'Valid13'			=>	'Event Delete Is Permanent!\\nAre you sure you want to delete the selected event(s)',
'Valid14'			=>	'Ok = YES Delete Event(s)',
'Valid15'			=>	'Cancel = NO Do NOT Delete Event(s)',
'Valid16'			=>	'Creating an event series will remove the selected events from any other series they may be a part of.\\nAre you sure you want to create a new series with the selected events?',
'Valid17'			=>	'Ok = YES Create New Event Series With the Selected Events',
'Valid18'			=>	'Cancel = NO Do Not Continue',
'Valid19'			=>	'Keyword Must Be Four (4) Characters or More in Length',

//	Feedback
'Feed01'			=>	'Event(s) deleted successfully.',
'Feed02'			=>	'Events updated successfully.',
'Feed03'			=>	'Events series created successfully.',
);?>