<?php
/********************************************************************/
/*
	@File Name: phpCal
	@Author: Rick Hopkins
	@Date: 11.07.2003
	@Description:
		[ PHP based calendar program which will create a calendar. It will display ]
		[ months, days, current day, navigation arrows to move from month to month, ]
		[ and year to year, and will also have the ability to color in days which contain ]
		[ events. The calendar will also be scalable in size, and have style editability. ]
		[ More features will be forth coming. ]
*/
/********************************************************************/
class phpCal
{ //class in session
	//declare variables
	// These variables all have to do with setting up calendar
	var $curMonth; //current month
	var $nextMonth; //next month
	var $prevMonth; //previous month
	var $curYear; //current year
	var $nextYear; //next year
	var $prevYear; //previous year
	var $monthTitleKey; //Key representing which month to pull out of $monthTitles array
	var $monthTitles; //Array of month titles
	var $curMonthTitle; //Title of current month
	var $daysOfWeek; //titles of days in week
	var $day; //numerical value of first day of month (0 = Sunday, 6 = Saturday)
	var $daysToCreate; //number of days to create
	var $eventDays; //array variable containing list of days with scheduled events
	
	// These variables all have to do with styling the calendar
	var $tableWidth; //width of calendar table
	var $tableColor; //color of calendar background
	var $tableBorder; //designates if table has border or not (0 = No, 0< = Yes, Width)
	var $tableBorderColor; //color of border
	var $tableSpacing; //space between table cells
	var $fontName; //font to be used
	var $fontSize; //size of font in pixels ex. 10px
	var $fontColor; //color of text
	var $numColor; //color of day numbers
	var $normalDayColor; //color of box on normal day
	var $normalDayRollOverColor; //color of normal box when rolled over
	var $curDayColor; //color of current day's box
	var $curDayRollOverColor; //color of current day's box when rolled over
	var $eventDayColor; //color of day's box which has events scheduled
	var $eventDayRollOverColor; //color of day's box which has events scheduled and has been rolled over
	var $dayLink; //name of file to link calendar days to
	var $calendarLink; //name of file to link calendar to
	
	var $fontStyleOne; //font style for month name and arrows
	var $fontStyleTwo; //font style for calendar days
	var $styleTable; //style commands for table
	var $borderStyleTdOne; //border style: bottom, right
	var $borderStyleTdTwo; //border style: bottom
	var $borderStyleTdThree; //border style: bottom left
	
	
	/************************************************/
	//function phpCal() will be the constructor class for phpCal class
	function phpCal($month, $year, $browsePast)
	{
		if($browsePast == 0 && ($month < date("m") && $year <= date("Y"))){
			$this->curMonth = date("m");
			$this->curYear = date("Y");
		} else {
			$this->curMonth = $month;
			$this->curYear = $year;
		}//end if
		
		$this->monthTitleKey = date("m", mktime(0, 0, 0, $this->curMonth, 1, $this->curYear)) - 1;
		$this->nextMonth = date("m", mktime(0, 0, 0, $this->curMonth + 1, 1, $this->curYear));
		$this->prevMonth = date("m", mktime(0, 0, 0, $this->curMonth - 1, 1, $this->curYear));
		$this->nextYear = date("Y", mktime(0, 0, 0, $this->curMonth + 1, 1, $this->curYear));
		$this->prevYear = date("Y", mktime(0, 0, 0, $this->curMonth - 1, 1, $this->curYear));
		$this->day = 1 - date("w", mktime(0, 0, 0, $this->curMonth, 1, $this->curYear));
		$this->daysToCreate = date("t", mktime(0, 0, 0, $this->curMonth, 1, $this->curYear));
		$this->eventDays = array();
		
		//setup default calendar styles
		$this->setStyleVars();
		$this->setStyles();
	}
	
	/************************************************/
	//function setStlyeVars() will set default values to all styles variables
	function setStyleVars()
	{
		$this->daysOfWeek = array(0 => "S", 1 => "M", 2 => "T", 3 => "W", 4 => "T", 5 => "F", 6 => "S");
		$this->monthTitles = array(0 => "January", 1 => "February", 2 => "March", 3 => "April", 4 => "May", 5 => "June", 6 => "July", 7 => "August", 8 => "September", 9 => "October", 10 => "November", 11 => "December");
		
		$this->tableWidth = 130;
		$this->tableColor = "#FFFFFF";
		$this->tableBorder = 1;
		$this->tableBorderColor = "#CCCCCC";
		$this->tableSpacing = 0;
		$this->fontName = "Verdana";
		$this->fontSize = "10";
		$this->fontColor = "#993300";
		$this->numColor = "#000000";
		$this->normalDayColor = "#FFFFFF";
		$this->normalDayRollOverColor = "#EEEEEE";
		$this->curDayColor = "#CCCCCC";
		$this->curDayRollOverColor = "#BBBBBB";
		$this->eventDayColor = "#C8C8AC";
		$this->eventDayRollOverColor = "B8B89B";
		$this->dayLink = sprintf("phpCal.php");
		$this->calendarLink = sprintf("phpCal.php");
	}
	
	/************************************************/
	//function setStyles() will set default values to all styles in calendar
	function setStyles()
	{
		$this->curMonthTitle = sprintf("%s '%s", $this->monthTitles[$this->monthTitleKey], substr($this->curYear, 2, 4));
		
		$this->fontStyleOne = sprintf(" style=\"font-family: %s; font-size: %spx; font-weight: normal; color: %s; margin: 0px; padding: 0px;\"", $this->fontName, $this->fontSize, $this->fontColor);
		$this->fontStyleTwo = sprintf(" style=\"font-family: %s; font-size: %spx; font-weight: normal; color: %s; margin: 0px; padding: 0px;\"", $this->fontName, $this->fontSize, $this->numColor);
		if ($this->tableBorder > 0){
			$this->styleTable = sprintf(" style=\"background-color: %s;border-top: %spx solid %s; border-left: %spx solid %s; border-right: %spx solid %s;\"", $this->tableColor, $this->tableBorder, $this->tableBorderColor, $this->tableBorder, $this->tableBorderColor, $this->tableBorder, $this->tableBorderColor);
			$this->borderStyleTdOne = sprintf(" style=\"border-bottom: %spx solid %s; border-right: %spx solid %s;\"", $this->tableBorder, $this->tableBorderColor, $this->tableBorder, $this->tableBorderColor);
			$this->borderStyleTdTwo = sprintf(" style=\"border-bottom: %spx solid %s;\"", $this->tableBorder, $this->tableBorderColor);
			$this->borderStyleTdThree = sprintf(" style=\"border-bottom: %spx solid %s; border-left: %spx solid %s;\"", $this->tableBorder, $this->tableBorderColor, $this->tableBorder, $this->tableBorderColor);
			} else {
			$this->styleTable = sprintf("");
			$this->borderStyleTdOne = sprintf("");
			$this->borderStyleTdTwo = sprintf("");
			$this->borderStyleTdThree = sprintf("");
			}
	}
	
	/************************************************/
	//function createCalendar() will piece the calendar together
	function createCal()
	{
		$day = $this->day;
		
		$cal = sprintf("<table width=\"%s\" cellpadding=\"%s\" cellspacing=\"%s\"%s>\n", $this->tableWidth, $this->tableSpacing, $this->tableSpacing, $this->styleTable);
		$cal .= sprintf("<tr onmouseover=\"style.cursor='pointer';\">\n");
		$cal .= sprintf("<td bgcolor=\"%s\" align=\"center\" onclick=\"window.location.href='%s?month=%s&year=%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s>&lt;</h1></td>\n", $this->normalDayRollOverColor, $this->calendarLink, $this->prevMonth, $this->prevYear, $this->curDayColor, $this->normalDayRollOverColor, $this->borderStyleTdOne, $this->fontStyleOne);
		$cal .= sprintf("<td bgcolor=\"%s\" colspan=\"5\" align=\"center\" onclick=\"window.location.href='%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s>%s</h1></td>\n", $this->normalDayRollOverColor, $this->calendarLink, $this->curDayColor, $this->normalDayRollOverColor, $this->borderStyleTdTwo, $this->fontStyleOne, $this->curMonthTitle);
		$cal .= sprintf("<td bgcolor=\"%s\" align=\"center\" onclick=\"window.location.href='%s?month=%s&year=%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s>&gt;</h1></td>\n", $this->normalDayRollOverColor, $this->calendarLink, $this->nextMonth, $this->nextYear, $this->curDayColor, $this->normalDayRollOverColor, $this->borderStyleTdThree, $this->fontStyleOne);
		$cal .= sprintf("</tr>\n");
		$cal .= sprintf("<tr>\n");
		$width = round($this->tableWidth / 7);
		for ($i = 0; $i < count($this->daysOfWeek); $i++){
			switch ($i){
				case 5:
					$cal .= sprintf("<td bgcolor=\"%s\" width=\"%s\" align=\"center\"%s><h1%s>%s</h1></td>\n", $this->normalDayRollOverColor, $width, $this->borderStyleTdTwo, $this->fontStyleTwo, $this->daysOfWeek[$i]);
					break;
				case 6:
					$cal .= sprintf("<td bgcolor=\"%s\" width=\"%s\" align=\"center\"%s><h1%s>%s</h1></td>\n", $this->normalDayRollOverColor, $width, $this->borderStyleTdThree, $this->fontStyleTwo, $this->daysOfWeek[$i]);
					break;
				default:
					$cal .= sprintf("<td bgcolor=\"%s\" width=\"%s\" align=\"center\"%s><h1%s>%s</h1></td>\n", $this->normalDayRollOverColor, $width, $this->borderStyleTdOne, $this->fontStyleTwo, $this->daysOfWeek[$i]);
					break;
				}
			}
		$cal .= sprintf("</tr>\n");
		
		$rows = 0;
		while ($day <= $this->daysToCreate){
			$cal .= sprintf("<tr onmouseover=\"style.cursor='pointer';\">\n");
			for ($i = 0; $i < 7; $i++){
				switch ($i){
					case 5:
						$borderStyle = $this->borderStyleTdTwo;
						break;
					case 6:
						$borderStyle = $this->borderStyleTdThree;
						break;
					default:
						$borderStyle = $this->borderStyleTdOne;
						break;
					}
				if (($day <= $this->daysToCreate) && ($day > 0)){
					$cal .= $this->setUpDay($borderStyle, $day);
					} else {
					$cal .= $this->setUpDay($borderStyle);
					}
				$day++;
				}
			$cal .= sprintf("</tr>\n");
			$rows++;
			}
		
		$cal .= sprintf("</table>\n");
		
		return $cal;
	}
	
	/************************************************/
	//function setUpDay() will color the given day accordingly
	function setUpDay($borderStyle, $day = "")
	{
		if (strlen($day) > 0){
			if (strlen($day) == 1){
				$day = sprintf("0%s", $day);
				}
			$dateCheck = sprintf("%s/%s/%s", $this->curMonth, $day, $this->curYear);
			if (in_array($dateCheck, $this->eventDays)){
				if (($day == date("j")) && ($this->curMonth == date("m")) && ($this->curYear == date("Y"))){
					$cell = sprintf("<td align=\"center\" bgcolor=\"%s\" onclick=\"window.location.href='" . CalRoot . "/%s?month=%s&day=%s&year=%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s><b>%s</b></h1></td>\n", $this->eventDayColor, $this->dayLink, $this->curMonth, $day, $this->curYear, $this->eventDayRollOverColor, $this->eventDayColor, $borderStyle, $this->fontStyleTwo, $day);
					} else {
					$cell = sprintf("<td align=\"center\" bgcolor=\"%s\" onclick=\"window.location.href='" . CalRoot . "/%s?month=%s&day=%s&year=%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s>%s</h1></td>\n", $this->eventDayColor, $this->dayLink, $this->curMonth, $day, $this->curYear, $this->eventDayRollOverColor, $this->eventDayColor, $borderStyle, $this->fontStyleTwo, $day);
					}
				} else {
				if (($day == date("j")) && ($this->curMonth == date("m")) && ($this->curYear == date("Y"))){
					$cell = sprintf("<td align=\"center\" bgcolor=\"%s\" onclick=\"window.location.href='" . CalRoot . "/%s?month=%s&day=%s&year=%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s><b>%s</b></h1></td>\n", $this->curDayColor, $this->dayLink, $this->curMonth, $day, $this->curYear, $this->curDayRollOverColor, $this->curDayColor, $borderStyle, $this->fontStyleTwo, $day);
					} else {
					$cell = sprintf("<td align=\"center\" bgcolor=\"%s\" onclick=\"window.location.href='" . CalRoot . "/%s?month=%s&day=%s&year=%s';\" onmouseover=\"style.backgroundColor='%s'\" onmouseout=\"style.backgroundColor='%s'\"%s><h1%s>%s</h1></td>\n", $this->normalDayColor, $this->dayLink, $this->curMonth, $day, $this->curYear, $this->normalDayRollOverColor, $this->normalDayColor, $borderStyle, $this->fontStyleTwo, $day);
					}
				}
			} else {
			$cell = sprintf("<td align=\"center\"%s><h1%s>&nbsp;</h1></td>\n", $borderStyle, $this->fontStyleTwo);
			}
		
		return $cell;
	}
	
	/************************************************/
	//function setEventDays() will allow a user to load an array of days with events for calendar coloration
	function setEventDays($events)
	{
		$this->eventDays = $events;
	}
	
	/************************************************/
	//function setFontVars() will allow the user to set the font variables
	function setFontVars($name, $size, $fColor, $nColor = "")
	{
		$this->fontName = $name;
		$this->fontSize = $size;
		$this->fontColor = $fColor;
		if (strlen($nColor) < 1){
			$this->numColor = $fColor;
			} else {
			$this->numColor = $nColor;
			}
		$this->setStyles();
	}
	
	/************************************************/
	//function setTableVars() will allow for editing the table values for size, color, and spacing
	function setTableVars($size, $color, $spacing)
	{
		$this->tableWidth = $size;
		$this->tableColor = $color;
		$this->tableSpacing = $spacing;
		$this->setStyles();
	}
	
	/************************************************/
	//function setTableBorders() will set the table border variables
	function setTableBorders($thickness, $color)
	{
		$this->tableBorder = $thickness;
		$this->tableBorderColor = $color;
		$this->setStyles();
	}
	
	/************************************************/
	//function setLinks() will allow for setting the calendar links
	function setLinks($dayLink, $calLink)
	{
		$this->dayLink = $dayLink;
		$this->calendarLink = $calLink;
	}
	
	/************************************************/
	//function setDaysOfWeek() will allow the user to change the titles of the days of the week
	function setDaysOfWeek($dayTitles)
	{
		$this->daysOfWeek = $dayTitles;
	}
	
	/************************************************/
	//function setMonthTitles() will allow the user to change the titles of months
	function setMonthTitles($monthTitles)
	{
		$this->monthTitles = $monthTitles;
		$this->setStyles();
	}
	
	/************************************************/
	//function setNormalDayColors() will allow for setting normal day background colors
	function setNormalDayColors($reg, $roll)
	{
		$this->normalDayColor = $reg;
		$this->normalDayRollOverColor = $roll;
		$this->setStyles();
	}
	
	/************************************************/
	//function setCurrentDayColors() will allow for setting current day background colors
	function setCurrentDayColors($reg, $roll)
	{
		$this->curDayColor = $reg;
		$this->curDayRollOverColor = $roll;
		$this->setStyles();
	}
	
	/************************************************/
	//function setEventDayColors() will allow for setting event day background colors
	function setEventDayColors($reg, $roll)
	{
		$this->eventDayColor = $reg;
		$this->eventDayRollOverColor = $roll;
		$this->setStyles();
	}
	
	/************************************************/
	//function setStartDay() will allow for changing the first day of the month
	function setStartDay($day) //1 = Sunday Starting Calendar, 1< = Monday Starting Calendar
	{
		if ($day > 1){
			$this->day = 1 - (date("w", mktime(0, 0, 0, $this->curMonth, 1, $this->curYear)) - 1);
			if ($this->day == 2){
				$this->day = -5;
				}
			} else {
			$this->day = 1 - date("w", mktime(0, 0, 0, $this->curMonth, 1, $this->curYear));
			}
	}
	
	/************************************************/
} //class out
/********************************************************************/
?>