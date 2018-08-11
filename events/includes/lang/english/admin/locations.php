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
'City'				=>	'City:',
'Postal'			=>	'Postal Code:',
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
'EventfulReq'		=>	'<b>eventful Username &amp; Password Required</b><br />Enter your eventful Username &amp; Password to submit this event.<br /><br />To skip this step in the future save your eventful account info in your Helios Calendar Settings',
'Username'			=>	'Username:',
'Passwrd1'			=>	'Password:',
'Passwrd2'			=>	'Confirm Password:',
'EventfulSubmit'	=>	'The following information about this location will be submitted:<ul><li>Name</li><li>Address</li><li>City</li><li>State</li><li>Country</li><li>Postal Code</li><li>Description</li><li>Website URL</li></ul>',
'EventfulUpdate'	=>	'Check to update on',
'EventfulAdd'		=>	'Check to add to',
'Accuracy'			=>	'Accuracy:',
'SearchLabel'		=>	'Location Merge Search',
'LocName'			=>	'Location Name:',

//	Form Buttons
'MergeLoc'			=>	'Merge Selected Locations',
'SaveLocation'		=>	'Save Location',
'MergeAsLoc'		=>	'Merge as Selected Location',
'Search'			=>	'Begin Search',

//	Browse Locations
'Page'				=>	'Page:',
'ResPer'			=>	'Locations Per Page:',

//	Validation
'Valid01'			=>	'Location Delete Is Permanent!\nAre you sure you want to delete this location?',
'Valid02'			=>	'Ok = YES Delete Location',
'Valid03'			=>	'Cancel = NO Do Not Delete Location',
'Valid04'			=>	'More selected locations required.\nPlease select at least two locations and try again.',
'Valid05'			=>	'Location could not be saved for the following reason(s):',
'Valid06'			=>	'*Location Name is Required',
'Valid07'			=>	'*Address is Required to Update Map Data',
'Valid08'			=>	'*City is Required to Update Map Data',
'Valid09'			=>	'*Postal Code is Required to Update Map Data',
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

//	Feedback
'Feed01'			=>	'Location Added Successfully!',
'Feed02'			=>	'Location Updated Successfully!',
'Feed03'			=>	'Location Deleted Successfully!',
'Feed04'			=>	'Location Added. Update Map Data Failed, or API Key Missing.',
'Feed05'			=>	'Location Updated. Update Map Data failed, or API Key Missing.',
'Feed06'			=>	'Location Deleted and Removed From Eventful Successfully!',
'Feed07'			=>	'Location Deleted Successfully. Eventful Connection failed.',
'Feed08'			=>	'Location Added and Submitted to Eventful Successfully!',
'Feed09'			=>	'Location Updated and Submitted to Eventful Successfully!',
'Feed10'			=>	'Location Saved Succesfully. Eventful Connection Failed.',
'Feed11'			=>	'Locations Could Not Be Merged. Please Try Again.',
'Feed12'			=>	'Locations Merged Successfully.'
);	?>