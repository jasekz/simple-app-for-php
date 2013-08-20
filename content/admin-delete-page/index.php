<?php if(!defined('SYSTEM')) die('no access');  

class Page_admin_delete_page {   

	public function __construct()
	{
		$this->admin = get_instance('admin'); 
	}
	
	public function index()
	{
		unset($_SESSION['delete_confirmed']); 
		$_SESSION['delete_confirmed'] = md5(time());
		$delete_page = $_GET['delete_page'];
		if(!$this->admin->page_exists($delete_page))
		{
			set_error_msg(PAGE_NOT_EXISTS);
			redirect('admin-pages');
		}
		if(!isset($delete_page) || substr($delete_page,0,2) == '__')
		{
			set_error_msg(ERROR_PAGE_DELETE);
			redirect('admin-pages');
		}
	}
	
	public function delete_page()
	{
		$delete_confirmed = $_POST['delete_confirmed'];
		$page = $_POST['page'];
		if(isset($delete_confirmed) &&  $delete_confirmed == $_SESSION['delete_confirmed'])
		{			
			if($this->admin->delete_page($page))
			{
				$this->admin->clear_cache();
				set_msg(SUCCESS_PAGE_DELETE);
			}
			else
			{
				set_error_msg(ERROR_PAGE_DELETE);
			}
			redirect('admin-pages');
		}
		set_error_msg(ERROR_PAGE_DELETE);
		redirect('admin-pages');
	}
}