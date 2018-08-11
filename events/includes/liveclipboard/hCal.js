/* Copyright 2006 Microsoft Corporation.  Microsoft's copyrights in this work are licensed under the Creative Commons */
/* Attribution-ShareAlike 2.5 License.  To view a copy of this license visit http://creativecommons.org/licenses/by-sa/2.5 */

function HCal(url, summary, description, dtstart, dtend, datetimestartlabel, location, uid, method, dtstamp, datetimeendlabel)
{
    this.Url = url;
    this.Summary = summary;
    this.Description = description;
    this.DtStart = dtstart;
    this.DtEnd = dtend;
    this.DateTimeStartLabel = datetimestartlabel;
    this.DateTimeEndLabel = datetimeendlabel;
    this.Location = location;
    this.UID = uid;
    this.Method = method;   
    this.DtStamp = dtstamp;

    this.formatType = "vevent";
    this.updateCallback;
    this.HTML;
    
    var self = this;
    
    this.clearProps = function()
    {
        self.Url = null;
        self.Summary = null;
        self.Description = null;
        self.DtStart = null;
        self.DtEnd = null;
        self.DateTimeLabel = null;
        self.Location = null;
        self.UID = null;
        self.Method = null;
        self.DtStamp = null;
        
        self.buildHtml();
    }
    
    this.buildHtml = function()
    {
        var UrlIsUID = (self.UID == self.Url);
        
        var hCalString = (UrlIsUID || !self.UID) ? "<span class=\"vevent\">" : "<span class=\"vevent uid\" title=\"" + self.UID + "\">";
               
        if (self.Url)
            hCalString += UrlIsUID ? "<a class=\"url uid\" href=\"" + self.Url + "\">" : "<a class=\"url\" href=\"" + self.Url + "\">";
        
        if (self.Summary)
            hCalString += "<span class=\"summary\">" + self.Summary + "</span>";
            
        if (self.Url)
            hCalString += "</a>";            

        if (self.Description)
            hCalString += "<span class=\"description\">" + self.Description + "</span>";
            
        if (self.DtStart)
            hCalString += "<abbr class=\"dtstart\" title=\"" + self.DtStart + "\">" + self.DateTimeStartLabel + "</abbr>";
            
        if (self.DtEnd)
        {
            hCalString += " - <abbr class=\"dtend\" title=\"" + self.DtEnd + "\">" + self.DateTimeEndLabel + "</abbr>";
        }
            
        if (self.Location)
            hCalString += ", at <span class=\"location\">" + self.Location + "</span>";
            
        if (self.Method)
            hCalString += "<span class=\"method\" title=\"" + self.Method + "\"></span>";
            
        if (self.DtStamp)
            hCalString += "<span class=\"dtstamp\" title=\"" + self.DtStamp + "\"></span>";                                                                                                                
        
        hCalString +=  "</span>";
        self.HTML = hCalString;
    }
    
    this.initFromXml = function(hCalXmlNode)
    {
        this.clearProps();
        self.xmlData = hCalXmlNode;
        self.parseXml(hCalXmlNode);
        
        
        if (hCalXmlNode.xml)
        {
            self.HTML =  hCalXmlNode.xml;
        }
        else
        {
            var serializer = new XMLSerializer();
            self.HTML = serializer.serializeToString(hCalXmlNode);
        }
    }    
    
    // Initialize all contact properties from the hCal XML segment and rebuild hCal HTML.
    this.initFromXmlString = function(hCalXmlString)
    {
        var hCalXmlNode;
        
        // IE 5+
        if (window.ActiveXObject)
        {
            hCalXmlNode = new ActiveXObject("Microsoft.XMLDOM");
            hCalXmlNode.async=false;
            hCalXmlNode.loadXML(hCalXmlString);
            hCalXmlNode.setProperty("SelectionLanguage", "XPath");
        }
        // Mozilla etc.
        else if (typeof DOMParser != "undefined")
        {
            var domParser = new DOMParser();
//          hCalXmlNode = domParser.parseFromString(hCalXmlString, 'application/xml');

//			Fix for Opera
            hCalXmlNode = document.importNode(domParser.parseFromString(hCalXmlString, 'application/xml').firstChild, true);
        }
        
        this.clearProps();
        self.HTML = hCalXmlString;
        self.xmlData = hCalXmlNode;
        self.parseXml(hCalXmlNode);
    }  
    
    this.parseXml = function(hCalXmlNode)
    {        
        // IE 5+
        if (window.ActiveXObject)
        {    
            var node;
                       
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//*[contains(@class, 'url')]/@href");
            if (node)
                self.Url = node.nodeTypedValue;
            
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//*[contains(@class, 'summary')]");
            if (node)
                self.Summary = node.nodeTypedValue;
            
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//*[contains(@class, 'description')]");
            if (node)
                self.Description = node.nodeTypedValue;
                
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtstart')]/@title");
            if (node)
                self.DtStart = node.nodeTypedValue;           
                
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtend')]/@title");
            if (node)
                self.DtEnd = node.nodeTypedValue;
            
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtstart')]");
            if (node)
                self.DateTimeStartLabel = node.nodeTypedValue;
                
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtend')]");
            if (node)
                self.DateTimeEndLabel = node.nodeTypedValue;                
            
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//*[contains(@class,'location')]");
            if (node)
                self.Location = node.nodeTypedValue;
            
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//a[contains(@class, 'uid')]/@href");
            if (node)
                self.UID = node.nodeTypedValue;  
            
            if (!self.UID)
            {        
                node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]/@title");
                if (node)
                    self.UID = node.nodeTypedValue;           
            }
                
            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//*[contains(@class, 'method')]/@title");
            if (node)
                self.Method = node.nodeTypedValue;

            node = hCalXmlNode.selectSingleNode("//*[contains(@class, 'vevent')]//*[contains(@class, 'dtstamp')]/@title");
            if (node)
                self.DtStamp = node.nodeTypedValue;                
        }
        // Mozilla etc.
        else if (typeof DOMParser != "undefined")
        {
            if (document.evaluate)
            { 
                var node;
                          
                node = document.evaluate("//*[contains(@class, 'vevent')]/*/@href", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.Url = node.textContent;
                
                node = document.evaluate("//*[contains(@class, 'vevent')]//*[contains(@class, 'summary')]", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.Summary = node.textContent;
                
                node = document.evaluate("//*[contains(@class, 'vevent')]//*[contains(@class, 'description')]", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.Description = node.textContent;
                    
                node = document.evaluate("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtstart')]/@title", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.DtStart = node.textContent;           
                    
                node = document.evaluate("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtend')]/@title", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.DtEnd = node.textContent;
                
                node = document.evaluate("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtstart')]", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.DateTimeStartLabel = node.textContent;
                    
                node = document.evaluate("//*[contains(@class, 'vevent')]//abbr[contains(@class, 'dtend')]", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.DateTimeEndLabel = node.textContent;                    
                
                node = document.evaluate("//*[contains(@class, 'vevent')]//*[contains(@class, 'location')]", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.Location = node.textContent;
                    
                node = document.evaluate("//*[contains(@class, 'vevent')]//a[contains(@class, 'uid')]/@href", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.UID = node.textContent;
                    
                if (!self.UID)
                {        
                    node = document.evaluate("//*[contains(@class, 'vevent')]/@title", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                    if (node)
                        self.UID = node.textContent;           
                }      
                    
                node = document.evaluate("//*[contains(@class, 'vevent')]//*[contains(@class, 'method')]/@title", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.Method = node.textContent;

                node = document.evaluate("//*[contains(@class, 'vevent')]//*[contains(@class, 'dtstamp')]/@title", hCalXmlNode, null, 0 /*XPathResult.ANY_TYPE*/, null).iterateNext();
                if (node)
                    self.DtStamp = node.textContent; 
            }
        }
    }

    self.buildHtml();
    self.initFromXmlString(self.HTML);
}