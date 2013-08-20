<?php if(!defined('SYSTEM')) die('no access');    	 

/**
 * SimpleApp
 * 
 * Open source application development framework for PHP 5 
 * 
 * Input Class: processes $_REQUEST variables
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

class Input { 
	
	/**
	 * Constructor
	 */
	public function __construct()    
	{
	}
	
	/**
	 * Iterates through $_REQUEST variables and calls the _clean() function on each 
	 *
	 * @return	void
	 * @access	public
	 */
	public function clean_request_vars() 
	{
		if (!empty($_POST))
		{
			foreach($_POST as $k=>$v)
			{
				if(is_array($v))
				{
					foreach($v as $k1=>$v1)
				    {
				    	$_POST[$k][$k1] = $v1 ? $this->_clean($v1) : null;
				    }
				}
				else 
				{
					 $_POST[$k] = $v ? $this->_clean($v) : null;
				}
			}
		}
		if (!empty($_GET))
		{
			foreach($_GET as $k=>$v)
			{
				if($_GET[$k] == '')
				{
					unset($_GET[$k]);
					continue;
				}
				if(is_array($v))
				{
					foreach($v as $k1=>$v1)
				    {
				    	 $_GET[$k][$k1] = $this->_clean($v1);
				    }
				}
				else 
				{
					$_GET[$k] = $this->_clean($v);
				}
			}
		}
	}
	
	/**
	 * Cleans passed value and returns it
	 *
	 * @return	mixed	cleaned variable
	 * @access	private
	 */
	private function _clean($value = null)
	{
		return trim(stripslashes($value));
	}
	
	/**
	 * Returns specified $_GET param
	 *
	 * @return	string
	 * @access	public
	 */
	public function get($key = null)
	{
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}
	
	/**
	 * Returns specified $_POST param
	 *
	 * @return	string
	 * @access	public
	 */
	public function post($key = null)
	{
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}
}