<?php    
session_start(); 
error_reporting(E_ALL); 
define('START_TIME', microtime(true)); // system execution time starting point   
define('APP_PATH',dirname(__FILE__));  // path to this directory
define('FILE_EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION)); // file extension to use for php files

/*************************************************************************************
****************** MUST DEFINE THESE PRIOR TO USE ************************************
**************************************************************************************
* DS:					Directory seperator win - '\\'; linux - '/'  
* TEMPLATES: 			path to templates to be used by browser(relative to web root)
* SYSTEM: 				location of simpleapp system folder relative to this file (wrap in realpath())
* CONTENT: 				location of content folder relative to this file (wrap in realpath())
* CONFIG: 				location of config folder relative to this file (wrap in realpath())
* ETC:	 				location of etc folder relative to this file (wrap in realpath()) 
*/
define('DS',"/"); 
define('TEMPLATES', '/simpleapp/templates');     
define('SYSTEM', realpath('system'));
define('CONTENT', realpath('content'));   
define('CONFIG', realpath('config')); 
define('ETC',realpath('etc')); 
/**************************************************************************************
***************************************************************************************
**************************************************************************************/

// if page is cached, serve it and we're done
if(file_exists(CONTENT.DS.'__cached'.DS.md5($_SERVER['REQUEST_URI']).'.txt'))
{
    echo file_get_contents(CONTENT.DS.'__cached'.DS.md5($_SERVER['REQUEST_URI']).'.txt');
    exit(0);  
}  

require_once(SYSTEM.DS.'core'.DS.'loader'.FILE_EXT); // load helpers and __autoload  
require_once(CONFIG.DS.'config'.FILE_EXT); // load configs

$language = Factory::get_instance('language'); 
$input = Factory::get_instance('input');
$auth = Factory::get_instance(AUTH_CLASS,$protected_pages); 

$language->load_language(); 

if (SEF_URLS && !$sys->is_valid_extension($sys->get_url())) 
{
	$sys->display('__404');
}

$input->clean_request_vars();  
$sys->display($sys->get_page());