<?php if(!defined('SYSTEM')) die('no access');     	 

/**
 * SimpleApp
 * 
 * Open source application development framework for PHP 5 
 * 
 * Language Class: loads language files for internationalization
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

class Language { 
	
	/**
	 * Consructor
	 */
	public function __construct()     
	{
	}
	
	/**
	 * Loads language files as specified by $_REQUEST variable
	 *
	 * @return	void
	 * @access	public
	 */
	public function load_language()
	{
		// dynamically load language
		if(isset($_POST[LANG]))
		{
			$_SESSION[LANG] = $_POST[LANG];
		}
		else if (isset($_GET[LANG]))
		{
			$_SESSION[LANG] = $_GET[LANG];
		}
		if(!isset($_SESSION[LANG]))
		{
			$_SESSION[LANG] = DEFAULT_LANGUAGE;
		}
		if(is_dir(CONTENT.DS.'__language'.DS.$_SESSION[LANG]) === false)
		{
			$_SESSION[LANG] = DEFAULT_LANGUAGE;
		}
		
		// load all files in the language dir
		if ($handle = opendir(CONTENT.DS.'__language'.DS.$_SESSION[LANG]))  
		{    
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..")  
				{
					include_once(CONTENT.DS.'__language'.DS.$_SESSION[LANG].DS.$file);  
				}
			 } 
			 closedir($handle); 
		}
	}
}