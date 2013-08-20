<?php if(!defined('SYSTEM')) die('no access'); 

class Page_admin_create_user {
	
	public function __construct()
	{
		$this->auth = get_instance(AUTH_CLASS);
	}
	
	public function index()
	{
		$data['user_access_levels'] = $this->auth->get_access_levels();
		$this->main_content = load('admin-create-user','main_content',$data);
	}

	public function create_user()
	{		
		$data['user_access_levels'] = $this->auth->get_access_levels();
		$data['first_name'] = $_POST['first_name'];
		$data['last_name'] = $_POST['last_name'];
		$data['email'] = $_POST['email'];
		$data['user_access_level'] = $_POST['user_access_level'];
		$password[0] = $_POST['password'][0];
		$password[1] = $_POST['password'][1];
		if(!$data['email'] || !$password[0] || !$password[1]) // required fields
		{
			set_error_msg(ENTER_REQUIRED);
		}
		else if($password[0] != $password[1]) // passwords do not match
		{
			set_error_msg(PASSWORDS_DO_NOT_MATCH);
		}
		else // do action
		{
			if(!$this->auth->create_user($data['first_name'], $data['last_name'], $data['email'], $password[0], $data['user_access_level']))
			{
					set_error_msg(USER_EXISTS);
			}
			else
			{
				set_msg(USER_CREATED);
				redirect('admin-users');
			}			
		}
		$this->main_content = $this->sys->load('admin-create-user','main_content',$data);
	}
}