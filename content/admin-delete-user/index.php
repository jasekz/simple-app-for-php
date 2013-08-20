<?php if(!defined('SYSTEM')) die('no access');  

class Page_admin_delete_user{

	public function __construct()
	{
		$this->auth = get_instance(AUTH_CLASS); 
	}

	public function index()
	{
		unset($_SESSION['delete_confirmed']); 
		$_SESSION['delete_confirmed'] = md5(time());
		$user_id = $_GET['user_id'];
		if(!$this->auth->user_exists($user_id))
		{
			set_error_msg(USER_NOT_EXISTS);
			redirect('admin-users');
		}
		if(!isset($user_id))
		{
			set_error_msg(ERROR_PAGE_DELETE);
			redirect('admin-users');
		}
		$data['user'] = $this->auth->get_user($user_id);
		$this->main_content = load('admin-delete-user','main_content',$data);
	}
	
	public function delete_user()
	{
		$user_id = $_POST['user_id'];
		if($this->auth->delete_user($user_id))
		{
			set_msg(SUCCESS_USER_DELETE);
		}
		else
		{
			set_error_msg(ERROR_USER_DELETE);
		}
		redirect('admin-users');
	}
}