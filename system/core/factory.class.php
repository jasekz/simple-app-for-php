<?php if(!defined('SYSTEM')) die('no access');      	 

/**
 * SimpleApp
 * 
 * Open source application development framework for PHP 5 
 * 
 * Factory Class: manages singleton instances
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class Factory { 

	private static $static_instances 	= array(); // singleton instances
	
	/**
	 * Returns a singleton instance
	 *
	 * @param	string	requested class instance
	 * @param	mixed	arg to be passed to requested class's consructor if class is going to be instantiated
	 * @param	mixed	arg to be passed to requested class's consructor if class is going to be instantiated
	 * @param	mixed	arg to be passed to requested class's consructor if class is going to be instantiated
	 * @return	&object	singleton instance of requested class
	 * @access	public
	 */
	public static function &get_instance($__class = 'sys',$__arg1 = null,$__arg2 = null,$__arg3 = null)
	{	
		if (!array_key_exists($__class,self::$static_instances))
		{
			$__class_name = strtolower($__class);
			self::$static_instances[$__class] = new $__class_name($__arg1,$__arg2,$__arg3);
		}
		return self::$static_instances[$__class];
	}
	
	/**
	 * Returns all instantiated instances
	 *
	 * @return	array	array of current instances
	 * @access	public
	 */
	public static function get_instances()
	{
		return self::$static_instances;
	}
	
	/**
	 * Returns a singleton instance of a page controller
	 *
	 * @param	string	requested class instance
	 * @param	mixed	arg to be passed to requested class's consructor if class is going to be instantiated
	 * @param	mixed	arg to be passed to requested class's consructor if class is going to be instantiated
	 * @param	mixed	arg to be passed to requested class's consructor if class is going to be instantiated
	 * @return	&object	singleton instance of requested class
	 * @access	public
	 */
	public static function &get_page_instance($__class = '__default',$__arg1 = null,$__arg2 = null,$__arg3 = null)
	{
		if (!array_key_exists('page_'.$__class,self::$static_instances))
		{
			$__class_name = 'Page_'.$__class;
			self::$static_instances['page_'.$__class] = new $__class_name($__arg1,$__arg2,$__arg3);
		}
		return self::$static_instances['page_'.$__class];
	}
}