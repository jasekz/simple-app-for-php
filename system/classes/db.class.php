<?php if(!defined('SYSTEM')) die('no access');	

/**
 * Provides database functionality  
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class Db {

	/**
	 * 
	 * @var MySQL link identifier
	 */
	private $conn;
	
	/**
	 * Connstructor
	 */
	public function __construct($host = null, $username = null, $password = null, $db = DB_DATABASE, $new_connection = false) 
	{
		$host = $host ? $host : DB_HOST;
		$username = $username ? $username : DB_USERNAME;
		$password = $password ? $password : DB_PASSWORD;
		
		$this->_connect($host, $username, $password, $db, $new_connection);
		$this->_select_db($db);
	}
	
	/**
	 * Connect to db
	 * 
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param bool $new
	 * @return bool
	 */
	private function _connect($host = null, $username = null, $password = null, $new = false)
	{
		if(!$this->conn = mysql_connect($host,$username,$password,$new)) return false;
		return true;
	}
	
	/**
	 * Select db
	 * 
	 * @param string $db
	 * @return void
	 */
	private function _select_db($db = null)
	{
		mysql_select_db($db,$this->conn);
	}
	
	/**
	 * Perform query (read & write)
	 *
	 * @param string $query
	 * @param array $args
	 * @return array|bool of results on success, false on failure
	 */
	public function query($query = null, $args = array())  
	{
		$write = true;
		if($query && strtoupper(substr($query,0,6)) == 'SELECT')
		{
			$write = false;
		}
		
		$bound = '';
		$segments = explode('?',$query);
		$count = 0;
		foreach($segments as $k=>$v)
		{
			$bound .= $v.(isset($args[$count]) ? "'".mysql_real_escape_string($args[$count])."'" : null);
			$count++;
		}
		$result = mysql_query($bound);
		if($result === false) 
		{
			echo mysql_error(); 
			die();
		}
		if(!$result) return false;
		
		if($write) return $result;
		 
		$rows = array();
		while($row = mysql_fetch_object($result))
		{
			array_push($rows,$row);
		}
		if(count($rows) == 0) return false;
		return $rows;
	}
	
	/**
	 * Write to database
	*
	* @param string sql query
	* @param array data
	* @param bool setting this will echo the bound query and kill program execution
	*
	* @return bool
	*/
	public function write($query = null, $args = array(), $debug = false)
	{
		return $this->query($query, $args, $debug);
	}
	
	/**
	 * Returns array of rows on success, empty array on failure
	 *
	 * @param string sql query
	 * @param array data
	 * @param bool setting this will echo the bound query and kill program execution
	 *
	 * @return array|bool
	 */
	public function rows($query = null, $args = array(), $debug = false)
	{
		return $this->query($query, $args, $debug);
	}
	
	/**
	 * Returns one record as object on succes, false on failure
	 * use this if you expect only one row, such as with LIMIT 1 queries
	 *
	 * @param string sql query
	 * @param array data
	 * @param bool setting this will echo the bound query and kill program execution
	 *
	 * @return object|bool
	 */
	public function row($query = null, $args = array(), $debug = false)
	{
		if(!$res = $this->query($query, $args, $debug)) return false;
		return $res[0];
	}
}