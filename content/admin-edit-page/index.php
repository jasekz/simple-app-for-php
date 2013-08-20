<?php if(!defined('SYSTEM')) die('no access');    

class Page_admin_edit_page { 

    public function __construct() 
    {
        $this->auth = get_instance(AUTH_CLASS); 
        $this->admin = get_instance('admin');  
		$this->protected_pages = $this->auth->get_protected_pages();     
    }
    
    public function index()
    {
        $edit_page = get('edit_page');
        if(!$edit_page || !$this->admin->page_exists($edit_page))
        {
            set_error_msg(PAGE_NOT_EXISTS);
            redirect('admin-pages');
        }
        $data['do_not_cache'] = in_array($edit_page,$this->do_not_cache); 
        $data['protected'] = array_key_exists($edit_page,$this->protected_pages);
        $data['access_level'] = $data['protected'] ? $this->protected_pages[$edit_page] : 0;
        $data['blocks'] = $this->admin->get_page_content($edit_page);
        $data['user_access_levels'] = $this->auth->get_access_levels();
        $this->main_content = load('admin-edit-page','main_content',$data);
    }


    public function edit_page()
    {
        $page = $_POST['page'];
        $cache = isset($_POST['cache']) ? true : false;
        $protect = isset($_POST['protect']) ? true : false;
        $user_access_level = $_POST['user_access_level'];
        $errors = array();
        if(!$this->admin->set_page_content($page,$_POST['block']))
        {
            array_push($errors,ERROR_PAGE_EDIT);
        }
        if(!$this->admin->set_page_properties($page, $cache, $protect, $user_access_level))
        {
            array_push($errors,ERROR_PAGE_EDIT);
        }
        if(empty($errors))
        {
            if($cache == 'on' && $protect == 'on')
            {
                set_warning_msg(WARNING_CACHED_PAGES_NOT_PROTECTED);
            }
            else
            {
                set_msg(SUCCESS_PAGE_EDIT);
            }
        }
        else
        {
            set_error_msg(implode(', ',$errors));
        }
        redirect('admin-edit-page','edit_page='.$page);
    }
	
	public function get_content_block()
	{
		$path = $_POST['file'];
		$arr = array_filter(explode('/', $path));
		$new_path = implode(DS, $arr);
		if(file_exists(CONTENT.DS.$new_path))
		{
			$data['path'] = $new_path;
			$data['content'] = htmlentities(file_get_contents(CONTENT.DS.$new_path));
			echo load('admin-edit-page','filetree/content_block',$data);
			exit(0);
		}
		else
		{
			echo SYSTEM_ERROR;
		}
		exit(0);
	}
	
	public function get_page_content()
	{
		$dir = str_replace('/', DS, $_POST['dir']);
		$data['file_tree'] = $this->admin->get_directory_content(CONTENT.DS.$dir);
		echo load('admin-edit-page','filetree/tree',$data);
		die();
	}
}