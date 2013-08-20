<?php if(!defined('SYSTEM')) die('no access');    

class Page_admin_pages{ 

    public function __construct()
    {
        $this->admin = get_instance('admin'); 
		$this->auth = get_instance(AUTH_CLASS);
		$this->pagination = get_instance('pagination');
    }
    
    public function index()
    {        
        $page_count = $this->admin->get_page_count();
        $start = isset($_GET['start']) ? $_GET['start'] : null;
		$data['protected_pages'] = $this->auth->get_protected_pages();
		$data['do_not_cache'] = $this->do_not_cache;
        $data['all_pages'] = $this->admin->get_all_pages(10, $start);       
        $data['pagination'] = $this->pagination->paginate($page_count,10,10);
        $this->main_content = load('admin-pages','main_content',$data);
    }
}