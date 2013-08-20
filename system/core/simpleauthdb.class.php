<?php if(!defined('SYSTEM')) die('no access');   	 

/**
 * SimpleApp
 * 
 * Open source application development framework for PHP 5 
 * 
 * Simpleauthdb Class: user authentication utilizing MySQL database
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

class Simpleauthdb { 

	private $protected_pages = array(); 
	private $salt_length = 8;
	private $db = null;
	
	/**
	 * Constructor
	 */
	public function __construct($protected_pages = array())    
	{
		$this->protected_pages = $protected_pages;
		$this->db = get_instance('db');
	}
	
	/**
	 * Creates a new user
	 *
	 * @param	string	first name
	 * @param	string	last name
	 * @param	string	email
	 * @param	string	password (plain text)
	 * @param	int	access level
	 * @return	bool
	 * @access	public
	 */
	public function create_user($first_name = '', $last_name = '', $email = '', $password = '', $user_access_level = 1)
	{
		// make sure user doesn't already exist
		$sql = "SELECT user_id
				FROM user
				WHERE email=?";
		if($this->db->query($sql,array($email))) return false;
		
		$data = array(
			$email,
			$this->get_hashed($password),
			$user_access_level,
			$first_name,
			$last_name
		);
		$sql = "INSERT INTO user
				SET 
					email=?,
					password=?,
					access_level=?,
					first_name=?,
					last_name=?
				";
		return $this->db->query($sql,$data);
	}
	
	/**
	 * Deletes the specified user
	 *
	 * @param	int	user id
	 * @return	bool
	 * @access	public
	 */
	public function delete_user($user_id = null) 
	{
		if(!$this->user_exists($user_id)) return false;
		$sql = "DELETE FROM user
				WHERE user_id=?";
		return $this->db->query($sql,array($user_id));
	}
	
	/**
	 * Returns available access levels
	 *
	 * @return	array	available access levels
	 * @access	public
	 */
	public function get_access_levels()
	{
		$sql = "SELECT *
				FROM access_level
				ORDER BY access_level_id";
		if(!$access_levels = $this->db->query($sql)) return false;
		foreach($access_levels as $k=>$v)
		{
			$access_levels_arr[$v->access_level_id] = $v->name;
		}
		return $access_levels_arr;
	}
	
	/**
	 * Hashes password and returns result
	 *
	 * @param	string	password to be hashed
	 * @return	string	hashed password
	 * @access	public
	 */
	function get_hashed($password = false)     
	{    	   
		$salt = substr(md5(uniqid(rand(), true)), 0, $this->salt_length); 
		$password = $salt . sha1($salt . $password);
		return $password;		
	}
	
	/**
	 * Hashes plain text password and checks agains supplied hashed password
	 *
	 * @param	string	plain text password
	 * @param	string	hashed password
	 * @return	bool
	 * @access	public
	 */
	function check_password($password = false,$hashed_password = false)
	{	   
		$salt = substr($hashed_password, 0, $this->salt_length);
		$password = $salt . sha1($salt . $password);     
		return $password == $hashed_password;
	}
	
	/**
	 * Returns list of protected pages
	 *
	 * @return	array	protected pages
	 * @access	public
	 */
	public function get_protected_pages()
	{
		return $this->protected_pages;
	}
	
	/**
	 * Returns total number of users
	 *
	 * @return	int	user count
	 * @access	public
	 */
	public function get_user_count($access_level = null)
	{
		$sql = "SELECT COUNT(user_id) as count
				FROM user";
		if(!$res = $this->db->query($sql)) return 0;
		return $res[0]->count;
	}
	
	/**
	 * Returns user data
	 *
	 * @param	int	user id
	 * @return	object
	 * @access	public
	 */
	public function get_user($user_id = null, $email = null)
	{
		if($email)
		{
			$condition = 'u.email=?';
			$arg = $email;
		}
		else
		{
			$condition = 'u.user_id=?';
			$arg = $user_id;
		}
		$sql = "SELECT u.*,al.*
				FROM user as u
				JOIN access_level as al
				ON u.access_level=al.access_level_id
				WHERE {$condition}";
		if(!$res = $this->db->query($sql,array($arg))) return false;
		return $res[0];
	}
	
	/**
	 * Returns array of users with all user data
	 *
	 * @param	int	max number of users to return
	 * @param	int	user number to start with
	 * @param	int	filter users by access level
	 * @return	array	array of user objects
	 * @access	public
	 */
	public function get_users($limit = null, $start = null, $access_level = null)
	{
		$sql = "SELECT u.*,al.name
				FROM user as u
				JOIN access_level as al
				ON al.access_level_id=u.access_level";
		return $this->db->query($sql);
	}

	/**
	 * Logs user in and sets session data
	 *
	 * @param	string	user email
	 * @param	string	user password
	 * @param	string	requested page
	 * @return	bool
	 * @access	public
	 */
	public function login($email = null, $password = null, $page = null) 
	{
		if(!$user = $this->get_user(null,$email)) return false;
		if($user->email == $email && $this->check_password($password,$user->password))
		{
			$_SESSION['session']['user'] = $user->email;
			if(!$user->password)
			{
				$_SESSION['session']['access_level'] = 3;
			}	
			else
			{
				$_SESSION['session']['access_level'] = $user->access_level;
			}
			if(!$this->logged_in($page))
			{
				$this->logout(); // unset session data
				return false;
			}			
			return true;
		}
		return false;
	}
	
	/** 
	 * Checks if current user has access to requested page
	 *
	 * @param	string	page to be displayed
	 * @return	bool
	 * @access	public
	 */
	public function logged_in($page = null)
	{
		$sys = Factory::get_instance();
		$pages = $this->protected_pages;
		
		// not logged in
		if(!isset($_SESSION['session']['user']) || !isset($_SESSION['session']['access_level'])) return false; 
		
		// logged in with appropriate access_level access
		if($pages[$page] >= $_SESSION['session']['access_level']) return true; 
		
		// logged in but inappropriate access_level access
		return 0; 
	}
	
	/** 
	 * Logs user out and destroys session
	 *
	 * @return	bool
	 * @access	public
	 */
	public function logout()
	{
	    foreach($_SESSION as $k=>$v)
	    {
	        unset($_SESSION[$k]);
	    }
	    if(empty($_SESSION)) return true;
	    return false;
	}
	
	/**
	 * Updates user data
	 *
	 * @param	string	first name
	 * @param	string	last name
	 * @param	string	email
	 * @param	string	plain text password
	 * @param 	int	access level for user
	 * @param	int	user id to be edited
	 * @return	bool
	 * @access	public
	 */
	public function update_user($first_name = '', $last_name = '', $email = '', $password = null, $user_access_level = 1, $user_id = null)
	{
		// make sure user exists
		$sql = "SELECT user_id
				FROM user
				WHERE user_id=?";
		if(!$this->db->query($sql,array($user_id))) return false;
		
		$data = array(
			$email,			
			$user_access_level,
			$first_name,
			$last_name,
			$user_id
		);
		if($password)
		{
			array_unshift($data,$this->get_hashed($password));
			$password = $password ? 'password=?,' : '';
		}
		
		$sql = "UPDATE user
				SET 
					{$password}
					email=?,					
					access_level=?,
					first_name=?,
					last_name=?					
				WHERE
					user_id=?
				";
		return $this->db->query($sql,$data);
	}
	
	/**
	 * Checks if specified user exists
	 *
	 * @param	int	user id
	 * @return	bool
	 * @access	public
	 */
	public function user_exists($user_id = NULL)
	{
		if($this->get_user($user_id)) return true;
		return false;
	}
}