<?php if(!defined('SYSTEM')) die('no access');  

class Page_admin_login {

	public function __construct() 
	{
		$this->auth = get_instance(AUTH_CLASS);
	}

	public function login()
	{
		$email = post('email');
		$password = post('password');
		$requested_page = post('success');
		$redirect_on_failure = post('failure');
		if($this->auth && $email && $password && $this->auth->login($email,$password,$requested_page))
		{
			redirect($requested_page);
		}
		// failed
		else if(isset($requested_page))
		{
			set_error_msg(ERROR_LOGIN);
			redirect($redirect_on_failure);
		}
		redirect(ADMIN_LOGIN_PAGE);
	}
}