<?php if(!defined('SYSTEM')) die('no access');

/**
 * SimpleApp
 *
 * Open source application development framework for PHP 5
 *
 * Global helper functions
 *
 * @package 	 SimpleApp
 * @author 		 Zbigniew Jasek
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */


/**
 * Checks if requested class file exists
 *
 * @param	string	requested class
 * @return  bool
 */
function class_available($class = null)
{
	if(file_exists(SYSTEM.DS.'classes'.DS.$class.'.class'.FILE_EXT) || file_exists(SYSTEM.DS.'core'.DS.$class.'.class'.FILE_EXT)) return true;
	return false;
} 

/**
 * Includes requested page/file and parses data
 *
 * @param	string	page to be included
 * @param	string	content block to be included
 * @param	array	data to be passed for parsing in the content block
 * @return  void
 */
function do_include($page = null, $file = null, $data = null) 
{
	if($data) extract($data);
	if(file_exists(CONTENT.DS.$page.DS.'views'.DS.$file.FILE_EXT))
	{
		include(CONTENT.DS.$page.DS.'views'.DS.$file.FILE_EXT); 
	} 
}

/**
 * Debugging helper
 *
 * @param	mixed	content to be displayed
 * @return  void
 */
function dump($msg = null) 
{
	echo '<pre>'; var_dump($msg); echo '</pre>';  
}

/**
 * Debugging helper
 *
 * @param	mixed	content to be written to debug file
 * @return  void
 */
function log_msg($msg = null, $append = false)
{
    $type = $append ? 'a+' : 'w';
    $handle = fopen(DEBUG_LOG,$type);
    ob_start();
	echo date('m.d.y G:i:s').'<br/>';
    echo '<pre>'; var_dump($msg); echo '</pre>';
    $out = ob_get_contents();
    ob_end_clean();
    fwrite($handle,$out.'<br/>');
    fclose($handle);
}

/**
 * Returns message set with set_*_msg() functions and discards message from session
 *
 * @return  string	message to be displayed
 */
function msg()
{
	if(isset($_SESSION['msg']))
	{
		$msg = $_SESSION['msg'];
		unset($_SESSION['msg']);
		return $msg;
	}	
}

/**
 * Returns hyperlink url
 *
 * @param	string	page to create a link for
 * @param	string	query string to be attached to hyperlink
 * @return  string	url to requested page
 */
function page($page = null, $query_str = null )
{
    if(SEF_URLS) return BASE_URL.'/'.$page.($page ? EXT : '').($query_str ? '?'.$query_str : '');
    return BASE_URL.'/index'.FILE_EXT.($page ? '?'.PAGE.'='.$page.($query_str ? '&'.$query_str : '') : '');
}

/**
 * Redirects to specified page
 *
 * @param	string	page to redirect to
 * @param	string	query string to be attached with redirect
 * @return  void
 */
function redirect($page = null,$query_str = null)
{
	if(substr($page,0,4) == 'http' || substr($page,0,9) == 'localhost')
	{
		header('location: '.urldecode($page));
	}
	else
	{
    	header('location:'.page($page, $query_str));
	}
    exit(0);
}

/**
 * Sets msg to be displayed by msg()
 *
 * @param	string	page to be displayed
 * @return  void
 */
function set_error_msg($msg)
{
    unset($_SESSION['msg']);
	$_SESSION['msg'] = '<div class="msg-error">'.$msg.'</div>';
}

/**
 * Sets msg to be displayed by msg()
 *
 * @param	string	page to be displayed
 * @return  void
 */
function set_warning_msg($msg)
{
    unset($_SESSION['msg']);
	$_SESSION['msg'] = '<div class="msg-warning">'.$msg.'</div>';
}

/**
 * Sets msg to be displayed by msg()
 *
 * @param	string	page to be displayed
 * @return  void
 */
function set_msg($msg)
{
    unset($_SESSION['msg']);
	$_SESSION['msg'] = '<div class="msg-success">'.$msg.'</div>';
}