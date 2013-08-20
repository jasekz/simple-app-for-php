<?php if(!defined('SYSTEM')) die('no access');   	 

/**
 * SimpleApp
 * 
 * Open source application development framework for PHP 5 
 * 
 * Simpleauth Class: user authentication utilizing text file
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

class Simpleauth { 

	private $users_file = null;  
	private $access_levels_file = null; 
	private $handle = null;
	private $protected_pages = array();
	private $salt_length = 8;
	
	/**
	 * Constructor
	 */
	public function __construct($protected_pages = array())   
	{
	    $this->users_file  = ETC.DS.'users.txt'; 
		$this->access_levels_file = ETC.DS.'access_levels.txt';
		$this->protected_pages = $protected_pages;
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
		$users = $this->get_users();
		// make sure user doesn't already exist
		foreach($users as $user)
		{
			if($user->email == $email && $user->access_level == $user_access_level)
			{
				return false;
			}
		}
		$user = new stdClass();
		$user->id = $users[count($users)-1]->id+1;
		$user->email = $email;
		$user->password = $this->get_hashed($password);
		$user->access_level = $user_access_level;
		$user->first_name = $first_name;
		$user->last_name = $last_name;
		array_push($users, $user);
		$users_txt = '';
		$sep = ':::';
		foreach($users as $user)
		{
			$users_txt .= $user->id
						 .$sep.$user->email.$sep
						 .$user->password.$sep
						 .$user->access_level.$sep
						 .$user->first_name.$sep
						 .$user->last_name."\n";
		}
		$handle = fopen($this->users_file,'w+');
		fwrite($handle, $users_txt);
		fclose($handle);
		return true;
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
		$users = $this->get_users();
		$users_txt = '';
		$sep = ':::';
		$user_exists = false;
		
		foreach($users as $user)
		{
			if($user->user_id == $user_id)
			{
				continue;		
			}
			else
			{
				$users_txt .= $user->user_id
							 .$sep.$user->email.$sep
							 .$user->password.$sep
							 .$user->access_level_id.$sep
							 .$user->first_name.$sep
							 .$user->last_name."\n";
			}
		}
		$handle = fopen($this->users_file,'w+');
		fwrite($handle, $users_txt);
		fclose($handle);
		return true;
	}
	
	/**
	 * Returns available access levels
	 *
	 * @return	array	available access levels
	 * @access	public
	 */
	public function get_access_levels()
	{
		$access_levels = file($this->access_levels_file);
		$access_levels_arr = array();
		foreach($access_levels as $access_level)
		{
			$chunks = explode(':::',$access_level);
			$access_levels_arr[trim($chunks[1])] = trim($chunks[0]);
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
	public function get_hashed($password = false)     
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
	public function check_password($password = false,$hashed_password = false)
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
	public function get_user_count()
	{
		$users = file($this->users_file);
		return count($users);
	}
	
	/**
	 * Returns user data
	 *
	 * @param	int	user id
	 * @return	object
	 * @access	public
	 */
	public function get_user($user_id = null)
	{
		$users = $this->get_users();
		foreach($users as $user)
		{
			if($user->user_id == $user_id)
			{
				return $user;
			}
		}
		return false;
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
		$users = file($this->users_file);
		$access_levels = $this->get_access_levels();
		$users_arr = array();
		foreach($users as $user)
		{
			$user = trim($user);
			$chunks = explode(':::',$user);
			$id = trim($chunks[0]);
			$user = new stdClass();
			$user->user_id = $id;
			$user->email = trim($chunks[1]);
			$user->password = trim($chunks[2]);
			$user->access_level_id = trim($chunks[3]);
			$user->name = $access_levels[$chunks[3]];
			$user->first_name = $chunks[4];
			$user->last_name = $chunks[5];
			array_push($users_arr,$user);
		}
		sort($users_arr);
		if($limit)
		{
			$users_arr = array_slice($users_arr,$start,$limit);
		}
		return $users_arr;
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
		$users = file($this->users_file);
		foreach($users as $user)
		{
			$chunks = explode(':::',$user);
			if(trim($chunks[1]) == trim($email) && $this->check_password($password,$chunks[2]))
			{
				$_SESSION['session']['user'] = $chunks[1];
				if(!isset($chunks[2]))
				{
					$_SESSION['session']['access_level'] = 3;
				}	
				else
				{
					$_SESSION['session']['access_level'] = $chunks[3];
				}
				if(!$this->logged_in($page))
				{
					$this->logout(); // unset session data
					return false;
				}			
				return true;
			}
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
	public function update_user($first_name = '', $last_name = '', $email = '', $password = '', $user_access_level = 1, $user_id = null)
	{
		$users = $this->get_users();
		$users_txt = '';
		$sep = ':::';
		$user_exists = false;

		foreach($users as $user)
		{
			if($user->user_id == $user_id)
			{
				$user_exists = true;
				$users_txt .= $user_id.$sep
							 .$email.$sep
							 .($password ? $this->get_hashed($password) : $user->password).$sep
							 .$user_access_level.$sep
							 .$first_name.$sep
							 .$last_name."\n";			
			}
			else
			{
				$users_txt .= $user->user_id
							 .$sep.$user->email.$sep
							 .$user->password.$sep
							 .$user->access_level_id.$sep
							 .$user->first_name.$sep
							 .$user->last_name."\n";
			}
		}
		if($user_exists)
		{
			$handle = fopen($this->users_file,'w+');
			fwrite($handle, $users_txt);
			fclose($handle);
			return true;
		}
		return $this->create_user($first_name, $last_name, $email, $password, $user_access_level);
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