<?php if(!defined('SYSTEM')) die('no access'); 
class Page_admin {
    
    public function __construct()
    {
        $this->auth = get_instance(AUTH_CLASS);
        $this->admin = get_instance('admin');
    }
	
	public function index()
	{
		// eventually there will be a home page, but for now just go to the page list
		redirect('admin-pages');
	}
    
    public function logout()
    {       
        if(!$this->auth->logout())
        {
            set_error_msg(SYSTEM_ERROR);            
        }
        redirect($_GET[REDIRECT]);
    }
    
    public function clear_cache()
    {       
        if($this->admin->clear_cache())
        {
            set_msg(SUCCESS_CACHE_CLEARED);
        }
        else
        {
            set_error_msg(ERROR_CACHE_CLEARED);
        }
        redirect($_GET[REDIRECT]);
    }
}
?>