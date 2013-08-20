<?php if(!defined('SYSTEM')) die('no access');  

/**
 * SimpleApp
 *
 * Open source application development framework for PHP 5
 *
 * Autoloading
 *
 * @package 	 SimpleApp
 * @author 		 Zbigniew Jasek
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

// load config files
if ($handle = opendir(CONFIG))    
{    
	while (false !== ($file = readdir($handle))) 
	{
    	if ($file != "." && $file != "..")  
		{
            include(CONFIG.DS.$file);  
		}
     } 
	 closedir($handle); 
}

// we need to load this before loading any helpers as they reference this instance  
require_once(SYSTEM.DS.'core'.DS.'factory.class'.FILE_EXT);
$sys = Factory::get_instance('sys',$do_not_cache); 
define('BASE_URL',$sys->get_base_url()); // convenience
define('BASE_SEGMENT',$sys->get_base_segment()); // convenience 

// load other system files 
if ($handle = opendir(SYSTEM.DS.'core')) 
{    
	while (false !== ($file = readdir($handle))) 
	{
    	if ($file != "." && $file != ".." && strpos($file,'loader'.FILE_EXT) === FALSE && strpos($file,'.class'.FILE_EXT) === false)  
		{
            require_once(SYSTEM.DS.'core'.DS.$file);
		}
     } 
	 closedir($handle);
}

/**
 * PHP's __autoload implementation
 */
function __autoload($__class = null)
{
	$__class = strtolower($__class);
	if(file_exists(SYSTEM.DS.'classes'.DS.$__class.'.class'.FILE_EXT))
	{
		require_once (SYSTEM.DS.'classes'.DS.$__class.'.class'.FILE_EXT);
	}
	else if(file_exists(SYSTEM.DS.'core'.DS.$__class.'.class'.FILE_EXT)) 
	{
		require_once (SYSTEM.DS.'core'.DS.$__class.'.class'.FILE_EXT);
	}
	else if(file_exists(CONTENT.DS.str_replace('page_','',$__class).DS.'index'.FILE_EXT))
	{
		require_once (CONTENT.DS.str_replace('page_','',$__class).DS.'index'.FILE_EXT);
	}
	else if(file_exists(CONTENT.DS.$__class.DS.'index'.FILE_EXT))
	{
		require_once (CONTENT.DS.$__class.DS.'index'.FILE_EXT);
	}
}