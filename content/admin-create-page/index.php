<?php if(!defined('SYSTEM')) die('no access');      

class Page_admin_create_page {
	
	public function __construct()
	{
		$this->admin = get_instance('admin');
		$this->auth = get_instance(AUTH_CLASS);		
	}
	
	public function index()
	{
		$data['user_access_levels'] = $this->auth->get_access_levels();		
		$this->main_content = load('admin-create-page','main_content',$data);
	}

	public function create_page()
	{
		$page_title = $_POST['page_title'];
		$cache = isset($_POST['cache']) ? true : false;
		$protect = isset($_POST['protect']) ? true : false;
		if($page_title)
		{
			$errors = array();
			if(!$this->admin->create_page($page_title))
			{
				array_push($errors,ERROR_PAGE_CREATE);
			}
			if(!$this->admin->set_page_properties($page_title, $cache, $protect))
			{
				array_push($errors,ERROR_PAGE_CREATE);
			}
			if(empty($errors))
			{
				if($cache == 'on' && $protect == 'on')
				{
					set_warning_msg(WARNING_CACHED_PAGES_NOT_PROTECTED);
				}
				else
				{
					set_msg(SUCCESS_PAGE_CREATE);
				}
				redirect('admin-pages');
			}
			else
			{
				set_error_msg('Error: '.implode(', ',$errors));
				redirect('admin-create-page');
			}			 
		}
		else
		{
			set_error_msg(ERROR_TITLE_REQUIRED);
			redirect('admin-create-page');
		}
	}
}