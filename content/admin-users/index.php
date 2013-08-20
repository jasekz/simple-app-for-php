<?php if(!defined('SYSTEM')) die('no access');  

class Page_admin_users{
     
    public function __construct()
    {
        $this->auth = get_instance(AUTH_CLASS);
    }
    
    public function index()
    {
        $start = get('start');
        $data['users'] = $this->auth->get_users(10,$start);
        $data['user_count'] = $this->auth->get_user_count();
        $data['pagination'] = pagination($data['user_count'],10,5);
        $this->main_content = load('admin-users','main_content',$data);
    }
}