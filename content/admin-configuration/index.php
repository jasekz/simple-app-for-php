<?php if(!defined('SYSTEM')) die('no access');  

class Page_admin_configuration {
	
	public function __construct()
	{
		$this->admin = get_instance('admin');
	}
	
	public function index() 
	{
		$data['languages'] = $this->admin->get_available_languages();
		$this->main_content = load('admin-configuration','main_content',$data);
	}
	
	public function edit()
	{
		$properties['DEBUG_LOG'] = $_POST['debug_log'];
		$properties['TEMPLATE'] = $_POST['template'];   
		$properties['ADMIN_TEMPLATE'] = $_POST['admin_template']; 
		$properties['EXT'] = isset($_POST['suffix']) ? '.'.str_replace('.','',$_POST['suffix']) : '';
		$properties['CACHE'] = $_POST['cache'] == 'true' ? true : false;
		$properties['SEF_URLS'] = $_POST['sef_urls'] == 'true' ? true : false;
		$properties['AUTH_CLASS'] = $_POST['auth_class'];	
		$properties['LOGIN_PAGE'] = $_POST['login_page'];	
		$properties['ADMIN_LOGIN_PAGE'] = $_POST['admin_login_page'];
		$properties['DEFAULT_LANGUAGE'] = $_POST['language'];
		
		if(DEFAULT_LANGUAGE != $_POST['language'])
		{
			$_SESSION[LANG] = $_POST['language'];
		}
			
		$errors = $this->_validate($properties);
		if(!empty($errors))
		{
			set_error_msg(implode('<br/>',$errors));
			redirect('admin-configuration');
		}
		if($this->admin->set_site_properties($properties))
		{
			if($properties['SEF_URLS'])
			{
				$from = APP_PATH.DS.'.htaccess__';
				$to = APP_PATH.DS.'.htaccess';
			}
			else
			{
				$from = APP_PATH.DS.'.htaccess';
				$to = APP_PATH.DS.'.htaccess__';
			}
			if(file_exists($from) && rename($from,$to))
			{
				set_msg(CONFIG_SETTINGS_SAVED);
			}
			else 
			{
			    set_error_msg(CONFIG_SETTINGS_NOT_SAVED_HTACCESS);
        		header('location:'.$_SERVER['HTTP_REFERER']);
        		exit(0);
			}
		}
		else
		{
			set_error_msg(CONFIG_SETTINGS_NOT_SAVED);
		}
		if(!$properties['CACHE'])
		{
			$this->admin->clear_cache();
		}
		if($properties['SEF_URLS'])
		{
			$redirect = BASE_URL.'/admin-configuration'.$properties['EXT'];
		}
		else
		{
			$redirect = BASE_URL . '/index'.FILE_EXT.'?'.PAGE.'=admin-configuration';
		}
		header('location:'.$redirect);
		exit(0);
	}
	
	private function _validate($properties = array())
	{
		$errors = array();
		if(!$this->admin->template_exists($properties['TEMPLATE']))
		{
			array_push($errors,str_replace('<VALUE/>',$properties['TEMPLATE'],TEMPLATE_NOT_EXISTS));
		}
		if($properties['TEMPLATE'] == '')
		{
			array_push($errors,MUST_PROVIDE_TEMPLATE_NAME);
		}
		if(!$this->admin->template_exists($properties['ADMIN_TEMPLATE']))
		{
			array_push($errors,str_replace('<VALUE/>',$properties['ADMIN_TEMPLATE'],TEMPLATE_NOT_EXISTS));
		}
		if($properties['ADMIN_TEMPLATE'] == '')
		{
			array_push($errors,MUST_PROVIDE_TEMPLATE_NAME);
		}
		if(!class_available($properties['AUTH_CLASS']))
		{
			array_push($errors,str_replace('<VALUE/>',$properties['AUTH_CLASS'],CLASS_NOT_EXISTS));
		}
		if(!$this->admin->page_exists($properties['LOGIN_PAGE']) || !$this->admin->page_exists($properties['LOGIN_PAGE']))
		{
			array_push($errors,str_replace('<VALUE/>',$properties['LOGIN_PAGE'],PAGE_NOT_EXISTS));
		}
		if(!$this->admin->page_exists($properties['ADMIN_LOGIN_PAGE']) || !$this->admin->page_exists($properties['ADMIN_LOGIN_PAGE']))
		{
			array_push($errors,str_replace('<VALUE/>',$properties['ADMIN_LOGIN_PAGE'],PAGE_NOT_EXISTS));
		}
		return $errors;
	}

}