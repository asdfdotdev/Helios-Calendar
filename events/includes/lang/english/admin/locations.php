<?php
$hc_lang_locations = array(

//	Add Location
'TitleAdd'			=>	'Add Location',
'InstructAdd'		=>	'Use the form below to add a location to your Helios Calendar.<br /><br />(<span style="color: #DC143C;">*</span>) = Required Field<br />(<span style="color: #0000FF;">*</span>) = Optional Fields, but required for <b>Map Data Update</b><br />(<span style="color: #008000;">*</span>) = Optional Field, but required for <b>Eventful submission</b>',

//	Edit Location
'TitleEdit'			=>	'Edit Location',
'InstructEdit'		=>	'Use the form below to edit the location.',

//	Browse Location
'TitleBrowse'		=>	'Manage Locations',
'InstructBrowse'	=>	'The list below contains locations available for your events. If you wish to remove a location(s) and assign it\'s events to another location use the Merge Location tool.',

//	Merge Location
'TitleMerge'		=>	'Merge Locations',
'InstructMerge1'	=>	'Step 1 of 3) Search for the locations you wish to merge. Location serch supports fulltext operators to allow for precise searching of locations to merge.',
'InstructMerge2'	=>	'Step 2 of 3) To merge any of the locations below, and assign their collective events to a single location, select the locations you wish to merge and click "Merge Selected Locations" below.',
'InstructMerge3'	=>	'Step 3 of 3) To merge the locations below select the location you want to merge the others with by selecting the radio button next to that location.<br /><br />The <b>selected location</b> will replace the other locations and their collective events will be assigned to it.',

//	Form Elements
'SelectAll'			=>	'Select All',
'DeselectAll'		=>	'Deselect All',
'NameLabel'			=>	'Name',
'StatusLabel'		=>	'Status',
'Public'			=>	'Public',
'AdminOnly'			=>	'Admin Only',
'NoLoc'				=>	'There are currently no locations available.',
'Add'				=>	'Add',
'Edit'				=>	'Edit',
'Details'			=>	'Location Details',
'Name'				=>	'Name:',
'Address'			=>	'Address:',
'Address2'			=>	'&nbsp;',
'City'				=>	'City:',
'Postal'			=>	'Zip Code:',
'Country'			=>	'Country:',
'Email'				=>	'Email:',
'Phone'				=>	'Phone:',
'Website'			=>	'Website:',
'Status'			=>	'Status:',
'Status0'			=>	'Admin Only',
'Status1'			=>	'Public',
'Map'				=>	'Map Data:',
'Latitude'			=>	'Latitude:',
'Longitude'			=>	'Longitude:',
'Update'			=>	'Download Map Data',
'ManualUpdate'		=>	'Manual Geocode Lookup',
'Description'		=>	'Description:',
'Optional'			=>	'Optional',
'ViewEventful'		=>	'View this Location on Eventful',
'UpdateE'			=>	'Update This Location on Eventful',
'AddE'				=>	'Add This Location to Eventful',
'CountryCode'		=>	'Country Code:',
'DistPub'			=>	'Distributed Publishing Options',
'DistPubNotice'		=>	'If the following checkboxes are disabled you can configure your API settings to enable them.',
'DistPubLinks'		=>	'Location Links:',
'EventfulView'		=>	'View on <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b>',
'EventfulLabelA'	=>	'Check to Add to <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b>',
'EventfulLabelU'	=>	'Check to Update at <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b>',
'EventfulLabelNE'	=>	'Cannot Update at <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b> (<i>Venue ID Downloaded</i>)',
'EventfulNotice'	=>	'The following information about this venue will be submitted:<ul><li>Name</li><li>Address</li><li>City</li><li>State(Region)</li><li>Country</li><li>Zip Code</li><li>Description</li><li>Website URL</li></ul><b>Note:</b> Before processing this venue\'s submission Helios will attempt to retrieve <b>only the Venue ID</b> for this venue from Eventful. If the Venue ID is retrieved the Eventful Venue will not be edited and Helios will submit your events for that Venue.',
'EventbriteView'	=>	'View on <b><span style="color:#F26822;">eventbrite</span></b>',
'EventbriteLabelA'	=>	'Check to Add to <b><span style="color:#F26822;">eventbrite</span></b>',
'EventbriteLabelU'	=>	'Check to Update at <b><span style="color:#F26822;">eventbrite</span></b>',
'EventbriteNotice'	=>	'The following information about this venue will be submitted:<ul><li>Name</li><li>Address</li><li>City</li><li>State(Region)</li><li>Country Code - <i>Use Select List</i></li><li>Zip Code</li></ul>',
'SearchLabel'		=>	'Location Merge Search',
'LocName'			=>	'Location Name:',
'Preview'			=>	'Preview These Coordinates',
'LinkMap'			=>	'Open Public Location Map',

//	Form Buttons
'MergeLoc'			=>	'Merge Selected Locations',
'SaveLocation'		=>	'Save Location',
'MergeAsLoc'		=>	'Merge as Selected Location',
'Search'			=>	'Begin Search',

//	Browse Locations
'Page'				=>	'Page:',
'ResPer'			=>	'Show Per Page:',

//	Validation
'Valid01'			=>	'Location Delete Is Permanent!\nAre you sure you want to delete this location?',
'Valid02'			=>	'Ok = YES Delete Location',
'Valid03'			=>	'Cancel = NO Do Not Delete Location',
'Valid04'			=>	'More selected locations required.\nPlease select at least two locations and try again.',
'Valid05'			=>	'Location could not be saved for the following reason(s):',
'Valid06'			=>	'*Location Name is Required',
'Valid07'			=>	'*Address is Required to Update Map Data',
'Valid08'			=>	'*City is Required to Update Map Data',
'Valid09'			=>	'*Zip Code is Required to Update Map Data',
'Valid10'			=>	'*Geocode Latitude Must Be Numeric',
'Valid11'			=>	'*Geocode Longitude Must Be Numeric',
'Valid12'			=>	'Please make the required changes and try again.',
'Valid13'			=>	'This will update Latitude and Longitude data with a new download from Google.\nAre you sure you want to download new map data for this Location?',
'Valid14'			=>	'Ok = YES, Save Location and Download New Data',
'Valid15'			=>	'Cancel = NO, Stop Save and DO NOT Download New Data',
'Valid16'			=>	'You must select a location to merge the others with.\nPlease select a location and try again.',
'Valid17'			=>	'*Email Address Format Invalid',
'Valid18'			=>	'To begin location merge you must supply location name search criteria.',
'Valid19'			=>	'Location name search criteria must be at least 4 (four) characters.',
'Valid20'			=>	'*Country Required for Eventful Submission',
'Valid21'			=>	'*Country Code Required for Eventbrite Submission',

//	Feedback
'Feed01'			=>	'Location created successfully.',
'Feed02'			=>	'Location updated successfully.',
'Feed03'			=>	'Location deleted successfully.',
'Feed04'			=>	'Location created successfully. Map Data retrieval failed.',
'Feed05'			=>	'Location updated successfully. Map Data retrieval failed.',
'Feed06'			=>	'Location deleted and removed from eventful successfully.',
'Feed07'			=>	'Location deleted successfully. Eventful removal failed.',
'Feed08'			=>	'Location added and submitted to Eventful successfully.',
'Feed09'			=>	'Location updated and submitted to Eventful successfully.',
'Feed10'			=>	'Location saved succesfully. Eventful connection failed.',
'Feed11'			=>	'Locations could not be merged, please try again.',
'Feed12'			=>	'Locations merged successfully.'
);	?>