<?php if(!defined('SYSTEM')) die('no access');      	 

/**
 * SimpleApp
 * 
 * Open source application development framework for PHP 5 
 * 
 * Sys Class: provides core functionality for the framework
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

class Sys { 

	private $segments 					= array(); // url segments    
	private $template_path  			= null; // path to template
	private $do_not_cache 				= array(); // no cache list 
	private $page 						= null; // current page
	private $controller 				= null; // reference to page's controller, if any 
	private $url 						= null; // current url
	private $base_url 					= null; // base url	
	private $auth						= null;
	
	/**
	 * Constructor
	 */
	public function __construct($do_not_cache = null)  
	{	 				
		$this->do_not_cache = $do_not_cache;
		$this->_parse_url();
	}
	
	/** 
	 * Processes http request and displays output
	 *
	 * @param	string	page to be displayed
	 * @return	void
	 * @access	public
	 */
	public function display($__page = '__default') 	  
	{
		// reset controller
		$this->controller = null;
		
		// if page starts with __ (except for __404), do not display
		if(isset($__page) && substr($__page,0,2) == '__' && $__page != '__404')
		{
			$this->display('__404');
			return false;
		}
		
	    // page we're interested in
		$this->page = $__page ? trim($__page) : '__default';
		
		// not found
		if (!is_dir(CONTENT.DS.$this->page))
		{
			$this->display('__404');
			return false;
		}

		// protected pages
		if ($__is_admin = $this->is_admin_page() || $this->is_member_page())  
		{			 
			$this->auth = Factory::get_instance(AUTH_CLASS);
			if (!$this->auth || !$__res = $this->auth->logged_in($this->page))
			{
			    if($__res === 0)
			    {
			        set_error_msg(NO_ACCESS);
			    }
				if($__is_admin)
				{
					redirect(ADMIN_LOGIN_PAGE,REDIRECT.'='.$this->page);
				}
				redirect(LOGIN_PAGE,REDIRECT.'='.$this->page);
			}
		}
		
		// controller/logic, if any
		if (file_exists(CONTENT.DS.$this->page.DS.'index'.FILE_EXT) && $this->page != '__404')
		{
			// class naming convention: Page_pagename
			$__class = 'Page_'.str_replace('-', '_', $this->page);
			if (!array_key_exists($__class,Factory::get_instances()))
			{
				// load controller
				include (CONTENT.DS.$this->page.DS.'index'.FILE_EXT);
				$__class_name = $__class_name = ucfirst($__class);	
				$__instance = Factory::get_instance($__class_name);	
				$__instance->do_not_cache = $this->do_not_cache;
				$__instance->segments = array_slice($this->segments,BASE_SEGMENT);
				$__instance->page = $this->page;
				$__instance->is_admin_page = $this->is_admin_page();
				$__instance->is_member_page = $this->is_member_page();
				$__instance->sys = $this;
				$this->controller = $__instance;
			}		
		}
				
		// get template
		$__output = $this->_get_template();
		
		// get page content 
		$__content_blocks = $this->_get_content_blocks();
		
		foreach($__content_blocks as $k=>$v)
		{
			// replace template tags with content
			$__output = str_replace('<'.strtoupper($k).'/>',$v,$__output); 
		}
		
		// cache?
	    if (!in_array($this->page,$this->do_not_cache)) 
    	{
    		$this->_cache_page($__output);
    	}
    	
		// set status messge, execution time, & memory usage
		$__output = str_replace('<MSG/>',msg(),$__output); 
		$__output = str_replace('<SERVER_STATS/>',microtime(true)-START_TIME.' : '.memory_get_usage()/1024/1024,$__output); 
		
		// clean up any unused tags
		$__output = preg_replace("/<[A-Z_]+\/>/",'',$__output);
		
		// and we're done
		header("Cache-Control: no-cache, must-revalidate"); 
		header("Expires: Sat, 26 Jul 1995 05:00:00 GMT");
		echo $__output; 
		exit(0);
	} 
	
	/**
	 * Returns the segment location of the application directory in the URL
	 *
	 * @return	int 
	 * @access	public
	 */
	public function get_base_segment()
	{
		return $this->base_segment;
	}
	
	/**
	 * Returns the base url, including the path to the application directory
	 *
	 * @return	string 
	 * @access	public
	 */
	public function get_base_url()
	{
		return $this->base_url;
	}
	
	/**
	 * Returns the $do_not_cache array
	 *
	 * @return	array
	 * @access	public
	 */
	public function get_do_not_cache()
	{
		return $this->do_not_cache;
	}
	
	/**
	 * Returns the current page
	 *
	 * @return	string
	 * @access	public
	 */
	public function get_page()
	{
		return $this->page;
	}
	
	/**
	 * Returns the full url to the current page
	 *
	 * @return	string
	 * @access	public
	 */
	public function get_url()
	{
		return $this->url;
	}
	
	/**
	 * Returns segmented url
	 *
	 * @return	array
	 * @access	public
	 */
	public function get_segments()
	{
		return $this->segments;
	}
	
	/**
	 * Is requested page an admin page?
	 *
	 * @return	bool
	 * @access	public
	 */
	public function is_admin_page()
	{
		$this->auth = Factory::get_instance(AUTH_CLASS);
		$__protected_pages = $this->auth->get_protected_pages();
		if(isset($__protected_pages[$this->page]) && ($__protected_pages[$this->page] == 1 || $__protected_pages[$this->page] == 2)) return true;
		return false;
	}
	
	/**
	 * Is requested page a member page?
	 *
	 * @return  bool
	 * @access	public
	 */
	public function is_member_page()
	{
		$this->auth = Factory::get_instance(AUTH_CLASS);
		$__protected_pages = $this->auth->get_protected_pages();
		if(isset($__protected_pages[$this->page]) && $__protected_pages[$this->page] == 3) return true;
		return false; 
	}
	
	/**
	 * Does the pseudo page extension match what is defined as EXT (SES only)
	 *
	 * @param	string	url to be evaluated
	 * @return  bool
	 * @access	public
	 */
	public function is_valid_extension($__url  = null)
	{
		// no page segment exists
		if(count($this->segments) == $this->base_segment) return true;
		
		// if an extension is specified, check url extension against it
		$__pos = strpos($__url,'?');				
		if (EXT)
		{
			$__len = strlen(EXT);
			if($__pos !== false)
			{
				$__ext = substr($__url,$__pos-$__len,$__len);				
			}
			else
			{
				$__ext = substr($__url,-$__len,$__len);
			}
			if ($__ext != EXT) return false;
		}
		else
		{
			// if no extension specified, make sure the last segment is 'plain'
			$__last_segment = end(explode('/',$__url));
			if($__pos !== false)
			{
				$__last_segment = explode('?',$__last_segment);
				$__last_segment = $__last_segment[0];		
			}
			$__disallowed = array('.');
			foreach($__disallowed as $v)
			{
				if(strstr($__last_segment,$v) !== false)
				{
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	 * Loads the specified page/file and parses data passed
	 *
	 * @param	string	page to be loaded
	 * @param	string	page file to be loaded
	 * @param	string	data to be be passed to the file
	 * @return	string	parsed page/file
	 * @access	public
	 */
	public function load($__page = null, $__file = null, $__data = null)
	{
		$__page = $__page ? $__page : $this->page;
    
	    if($__data) extract($__data); 
		$__out = '';
		if (file_exists(CONTENT.DS.$__page.DS.'views'.DS.$__file.FILE_EXT))
		{
			ob_start();
			include (CONTENT.DS.$__page.DS.'views'.DS.$__file.FILE_EXT);
			$__out = ob_get_contents();
			ob_end_clean();
		}
		return $__out;
	}
	
	/**
	 * Caches page, if set to do so in config
	 *
	 * @param	string	page content to be cached
	 * @return	bool	true on success, false on failure
	 * @access	private
	 */
	private function _cache_page($__content = null)
	{
		if (CACHE == false) return false;
	    $__handle = fopen(CONTENT.DS.'__cached'.DS.md5($_SERVER['REQUEST_URI']).'.txt','w');
	    fwrite($__handle, $__content); 
	    return true; 
	}
	
	/**
	 * Returns current page's content blocks ready for display
	 *
	 * @return	array	parsed content blocks ready for display
	 * @access	private
	 */
	private function _get_content_blocks()
	{
		$__content_blocks = array();

		// controller/logic, if any
		if ($this->controller)
		{
			if (method_exists($this->controller, 'index2'))
			{
				$this->controller->index2();
			}
			else
			{
				// look for function to call (if any) in page's index.php; order of priority: post, get, uri segment
				$__cmd = 'index'; // default function
				if (isset($_POST[CMD]))
				{
					$__cmd = str_replace('-','_',$_POST[CMD]);
				}
				else if (isset($_GET[CMD]))
				{
					$__cmd = str_replace('-','_',$_GET[CMD]);
				}
				else if (isset($this->segments[$this->base_segment+2]))
				{
					$__cmd = str_replace('-','_',$this->segments[$this->base_segment+2]);
				}
				if(method_exists($this->controller, $__cmd))
				{
					$this->controller->$__cmd();
				}
				else if($__cmd != 'index')
				{
					$this->display('__404');
					return false;
				}
			}
		}
		
		// get page content 
		if(@$__handle = opendir(CONTENT.DS.$this->page.DS.'views')) 
		{
		    $__exclude = array('.','..','index'.FILE_EXT);
			while (false !== ($__file = readdir($__handle))) 
			{
				if (!in_array($__file,$__exclude) && is_dir($__file) === false)  
				{
				    // content block overridden with matching variable, i.e. $main_content = 'over ride main_conent.php'
					$__override = substr($__file,0,-4);
					if (isset($this->controller->{$__override}))
					{
						$__page_content = $this->controller->{$__override};
						// use template blocks if content wrapped in <TEMPLATE> tags
						$__page_content = $this->_scrape($__page_content);
					}
					else 
					{
					    // get content block
						ob_start();
						include (CONTENT.DS.$this->page.DS.'views'.DS.$__file);	
						
						// use template blocks if content wrapped in <TEMPLATE> tags
						$__page_content = $this->_scrape(ob_get_contents());
						
						// use default content?
						if (strpos($__page_content,'<USE_DEFAULT/>') !== FALSE)
						{
							if (file_exists(CONTENT.DS.'__default'.DS.'views'.DS.$__file)) 
							{
								unset ($__page_content);
								ob_start();
								include (CONTENT.DS.'__default'.DS.'views'.DS.$__file);
								$__page_content = ob_get_contents();
								ob_end_clean();
								$__page_content = $this->_scrape(str_replace('<USE_DEFAULT/>','',$__page_content));	
							}
						}
						ob_end_clean();
					}
					$__name = explode('.',$__file);
					$__content_blocks[$__name[0]] = $__page_content;
				}
			 }
		}
		@closedir($__handle);
		return $__content_blocks;
	}
		
	/**
	 * Returns template
	 *
	 * @return	string	template html markup
	 * @access	private
	 */
	private function _get_template()
	{
		if ($this->is_admin_page())
		{
			$this->template_path = TEMPLATES.DS.ADMIN_TEMPLATE;			
		}
		else
		{
			$this->template_path = TEMPLATES.DS.TEMPLATE;
		}
				
		ob_start();
		$__file = isset($this->controller) && isset($this->controller->template) ? $this->controller->template : 'index'.FILE_EXT;
		if (!defined('TEMPLATE_PATH')) define('TEMPLATE_PATH',str_replace('\\','/',$this->template_path)); // convenience
		
		$__output = file_get_contents($_SERVER['DOCUMENT_ROOT'].$this->template_path.DS.$__file);
		$__output = str_replace('<TEMPLATE_PATH/>',TEMPLATE_PATH,$__output);
		ob_end_clean();
		
		return $__output;
	}
	
	/**
	 * Parses current url and sets class variable
	 *
	 * @return	void
	 * @access	private
	 */
	private function _parse_url()
	{
		$this->segments = array_filter(explode('/',$_SERVER['REQUEST_URI'])); // all segments
		$__current_dir = substr(APP_PATH,strlen($_SERVER['DOCUMENT_ROOT'])); // location of index.php relative to web root
		$this->base_segment = count(array_filter(explode(DS,$__current_dir)));	// depth of index.php relative to web root
		
		// url to index.php (front controller)
		$__url = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') 
		{
			$__url .= 's';
		}
		$__url .= '://';
		if ($_SERVER["SERVER_PORT"] != "80") 
		{
			$this->url = $__url.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			$this->base_url = $__url.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$__current_dir;
		} 
		else 
		{
			$this->url = $__url.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			$this->base_url = $__url.$_SERVER["SERVER_NAME"].($__current_dir ? '/'.substr($__current_dir,1) : '');
		}

		foreach ($this->segments as $k=>$v)
		{
			// seperate last segment from query string
			if (strpos($v,'?') !== FALSE)
			{
				if($__chunks = explode('?',$v))
				{
					$this->segments[$k] = $__chunks[0];
				}					
			}
		}
		
		// reset $_GET
		$__get = explode('?',$_SERVER['REQUEST_URI']);
		if (isset($__get[1]))
		{
			$__vars = explode('&',$__get[1]);
			foreach ($__vars as $k=>$v)
			{
				$__chunks = explode('=',$v);
				if (isset($__chunks[0]) && isset($__chunks[1]))
				{
					$_GET[$__chunks[0]] = $__chunks[1];
				}
			}
		}
		
		if(SEF_URLS && count($this->segments) > 0)
		{
			// remove extension from last segment
			$__segment = end($this->segments);
			$__chunks = explode('.',$__segment); 
			$this->segments[count($this->segments)] = $__chunks[0];
			$this->page = isset($this->segments[$this->base_segment+1]) ? $this->segments[$this->base_segment+1] : null;
		}	
		else
		{
			$this->page = isset($_GET[PAGE]) ? $_GET[PAGE] : null;
		}	
		
	}
	
	/**
	 * Fills 'template blocks' with content
	 *
	 * @param	string	content blocks to be filled with content
	 * @return	string	content blocks to be displayed
	 * @access	private
	 */
	private function _scrape($__content = null)
	{
        $__regex = '/<TEMPLATE block="[^>]+">(.+?)<\/TEMPLATE>/ism';
        preg_match_all($__regex,$__content,$__match,PREG_PATTERN_ORDER);
        foreach ($__match[0] as $k=>$v) 
        {
                $__cont = $__match[1][$k];
                $__regex = '/block="(.+?)"/';
                preg_match($__regex,$v,$__match2);
                $__tpl = trim($__match2[1]);
                if (!file_exists(($_SERVER['DOCUMENT_ROOT'].DS.$this->template_path.DS.'blocks'.DS.$__tpl.FILE_EXT)))
                {
                    continue;
                }
                ob_start();
                include ($_SERVER['DOCUMENT_ROOT'].DS.$this->template_path.DS.'blocks'.DS.$__tpl.FILE_EXT);
                $__c = ob_get_contents();
                ob_end_clean();
                $__c = str_replace('<CONTENT/>',$__cont,$__c);
                $__content = str_replace("<TEMPLATE block=\"$__tpl\">$__cont</TEMPLATE>",$__c,$__content);
        }
        return $__content;
	}
}