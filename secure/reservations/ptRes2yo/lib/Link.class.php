<?php
/**
* XHTML link class
* Creates or prints an XHTML valid link
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 11-08-03
* @package Link
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

class Link {
	var $url;
	var $text;
	var $_class;
	var $style;
	var $text_on_over;

	/**
	* Link Constructor
	* Creates a new XHTML valid link
	* @param string $url url to link to
	* @param string $text text of link
	* @param string $class link class
	* @param string $style inline style of link (overrides class)
	* @param string $text_on_over text to display in status bar onmouseover
	* @param string $on_over javascript to call onmouseover
	*/
	function Link($url=null, $text=null, $class=null, $style=null, $text_on_over=null) {
		$this->url = $url;
		$this->text = $text;
		$this->_class = $class;
		$this->style = $style;
		$this->text_on_over = addslashes($text_on_over);
	}
	
	//---------------------------------------------
	// Setter functions
	//---------------------------------------------
	/**
	* Set the url of the link
	* @param string $url url to link to
	*/
	function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	* Set the text of the link
	* @param string $text text of link
	*/
	function setText($text) {
		$this->text = $text;
	}
	
	/**
	* Set the class of the link
	* @param string $class link class
	*/
	function setClass($class) {
		$this->_class = $class;
	}
	
	/**
	* Set the inline style of the link
	* @param string $style inline style of link (overrides class)
	*/
	function setStyle($style) {
		$this->style = $style;
	}
	
	/**
	* Set the text onmouseover
	* @param string $text_on_over text to display in status bar onmouseover
	*/
	function setTextOnOver($text_on_over) {
		$this->text_on_over = addslashes($text_on_over);
	}

	//=============================================
	
	
	//---------------------------------------------
	// Getter functions
	//---------------------------------------------
	/**
	* Return the url of the link
	* @return string $url url to link to
	*/
	function getUrl() {
		return $this->url;
	}
	
	/**
	* Return the text of the link
	* @return string $text text of link
	*/
	function getText() {
		return $this->text;
	}
	
	/**
	* Return the class of the link
	* @return string $class link class
	*/
	function getClass() {
		return $this->_class;
	}
	
	/**
	* Return the inline style of the link
	* @return string $style inline style of link (overrides class)
	*/
	function getStyle() {
		return $this->style;
	}
	
	/**
	* Return the text onmouseover
	* @return string $text_on_over text to display in status bar onmouseover
	*/
	function getTextOnOver() {
		return stripslashes($this->text_on_over);
	}
		
	//=============================================
	
	
	/**
	* Print out a link without creating a new Link object
	* @param string $url url to link to
	* @param string $text text of link
	* @param string $class link class
	* @param string $style inline style of link (overrides class)
	* @param string $text_on_over text to display in status bar onmouseover
	* @param string $on_over javascript to call onmouseover
	*/
	function doLink($url=null, $text=null, $class=null, $style=null, $text_on_over=null) {
		echo $this->getLink($url, $text, $class, $style, $text_on_over);		
	}
	
	/**
	* Prints out the link using the class values
	* @param none
	* @see doLink()
	*/
	function printLink() {
		$this->doLink($this->url, $this->text, $this->_class, $this->style, $this->text_on_over);		
	}
	
	/**
	* Returns the HTML for the link with given parameters
	* @param string $url url to link to
	* @param string $text text of link
	* @param string $class link class
	* @param string $style inline style of link (overrides class)
	* @param string $text_on_over text to display in status bar onmouseover
	* @param string $on_over javascript to call onmouseover
	* @return string of HTML for link
	*/
	function getLink($url=null, $text=null, $class=null, $style=null, $text_on_over=null) {
		$text_on_over = (!is_null($text_on_over)) ? $text_on_over : $text;	// Use passed in text on mouse over, else just use link text
		return "<a href=\"$url\" class=\"$class\" style=\"$style\" onmouseover=\"javascript: window.status='" . addslashes($text_on_over) . "'; return true;\" onmouseout=\"javascript: window.status=''; return true;\">$text</a>\n";
	}
}
?>