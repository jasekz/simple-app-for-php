<?php if(!defined('SYSTEM')) die('no access'); 

class Page_login { 

	public function __construct() 
	{
		$this->auth = get_instance(AUTH_CLASS);
	}

	public function login()
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		$requested_page = $_POST['success'];
		$redirect_on_failure = $_POST['failure'];
		if(isset($email) && isset($password) && $this->auth->login($email,$password,$requested_page))
		{
			redirect($requested_page);
		}
		// failed
		else if(isset($requested_page))
		{
			set_error_msg(ERROR_LOGIN);
			redirect($redirect_on_failure);
		}
		redirect(LOGIN_PAGE);
	}
}