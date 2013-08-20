<?php if(!defined('SYSTEM')) die('no access');  

class Page_admin_edit_user{

	public function __construct() {
		$this->auth = get_instance(AUTH_CLASS); 		 
	}

	function index(){		
		if(isset($_GET['user_id']) && !$data['user'] = $this->auth->get_user($_GET['user_id']))
		{
			set_error_msg('This user does not exist');
			redirect('admin-users');
		}
		$data['user_access_levels'] = $this->auth->get_access_levels();
		$this->main_content = load('admin-edit-user','main_content',$data);
	}

	function edit_user()
	{
		$email = $_POST['email'];
		$password[0] = $_POST['password'][0];
		$password[1] = $_POST['password'][1];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$user_access_level = $_POST['user_access_level'];
		$user_id = $_POST['user_id'];
		if(!$email) // required fields
		{
			set_error_msg(ENTER_REQUIRED);
		}
		else if($password[0] != $password[1]) // passwords do not match
		{
			set_error_msg(PASSWORDS_DO_NOT_MATCH);
		}
		else // do action
		{
			if(!$this->auth->update_user($first_name, $last_name, $email, $password[0], $user_access_level, $user_id))
			{
					set_error_msg(USER_EXISTS);
			}
			else
			{
				set_msg(USER_UPDATED);
				redirect('admin-users');
			}			
		}
		redirect('admin-edit-user','user_id='.$user_id);
	}
}