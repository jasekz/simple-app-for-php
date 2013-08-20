<?php if(!defined('SYSTEM')) die('no access');

/**
 * SimpleApp
 *
 * Open source application development framework for PHP 5
 *
 * Global shortcuts for commonly used class functions
 *
 * @package 	 SimpleApp
 * @author 		 Zbigniew Jasek
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

$sys = get_instance();
$input = get_instance('input');
$pagination = get_instance('pagination');

/**
 * See Input::get()
 */
function get($key = null)
{
	global $input;
	return $input->get($key);
}

/**
 * See System::get_current_url()
 */
function get_current_url() 
{
	global $sys;
	return $sys->get_url();
}

/**
 * See Factory::get_instance()
 */
function get_instance($class = 'sys',$arg1 = null,$arg2 = null,$arg3 = null)
{
    return Factory::get_instance($class,$arg1,$arg2,$arg3);
}

/**
 * See System::is_admin_page()
 */
function is_admin_page()
{
	global $sys;
	return $sys->is_admin_page();
}

/**
 * See System::is_member_page()
 */
function is_member_page()
{
	global $sys;
	return $sys->is_member_page();
}

/**
 * See System::load()
 */
function load($page = null, $file = null, $data = null)
{
	global $sys;
	return $sys->load($page, $file, $data);
}

/**
 * See Pagination::paginate()
 */
function pagination($total_rows = 0, $limit = 5, $max_page_links = 5, $status = true)
{
    global $pagination;
    return $pagination->paginate($total_rows, $limit, $max_page_links, $status);
}

/**
 * See Input::post()
 */
function post($key = null)
{
	global $input;
	return $input->post($key);
}