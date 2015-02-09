<?php if(!defined('SYSTEM')) die('no access');  

/**
 * Admin class 
 * Provides functions for managing content  
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class Admin{	

	/**
	 * Constructor
	 *
	 */
	public function __construct() 
	{

	}
	
	/**
	 * Deletes cached pages from the 'cache' dir
	 * 
	 * @param string $page
	 * @return bool
	 */
	public function clear_cache($page = null) 
	{
		if (@$handle = opendir(CONTENT.DS.'__cached')) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && is_dir(CONTENT.DS.'__cached'.DS.$file) === false)  
				{
					if(!unlink(CONTENT.DS.'__cached'.DS.$file))
					{
						return false;
					}
				}
			 }
		}
		return true;
	}
	
	/**
	 * Creates a new SimpleApp page:
	 * - create new direcotry
	 * - copy default content into the new dir
	 *
	 * @param string $page
	 * @return bool
	 */
	public function create_page($page = null)            
	{
		$search = array(' ');
		$replace = array('-');
		$page = strtr(trim($page), array_combine($search, $replace));
		if (!is_dir(CONTENT.DS.$page))    
		{
			$this->_copy_recursively(CONTENT.DS.'__default',CONTENT.DS.$page,$page); 
		}
		else
		{
			return false;
		}
		return true;
	}

	/**
	 * Deletes a SimpleApp page by recursively deleting the directory
	 *
	 * @param string $page
	 * @return bool
	 */
	public function delete_page($page = null)
	{
		if (is_dir(CONTENT.DS.$page))  
		{
			$deleted = $this->_delete_dir(CONTENT.DS.$page);
			$config_set = $this->set_page_properties($page,true,false);
			if($deleted && $config_set) return true;
			return false;
		}
		return false;
	}

	
	/**
	 * Returns a list of all SimpleApp pages
	 *
	 * @param int $limit
	 * @param int $start
	 * @return array of directory names in the 'content' dir (page names)
	 */
	public function get_all_pages($limit = null, $start = null)
	{
		$do_not_include = array('__cached','__language');
		$pages = array();
		$start = $start ? $start : 0; 
		if (@$handle = opendir(CONTENT)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && is_dir(CONTENT.DS.$file) !== false && !in_array($file,$do_not_include))  
				{
					array_push($pages,$file);	
				}
			 }
		}
		sort($pages);
		if($limit)
		{
			$pages = array_slice($pages,$start,$limit);
		}
		return $pages;
	}
	
	/**
	 * Returns list of supported languages
	 * 
	 * @return array of language names
	 */
	public function get_available_languages()
	{
		$languages = array();
		if (@$handle = opendir(CONTENT.DS.'__language')) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..")  
				{
					array_push($languages,$file);
				}
			 }
		}
		return $languages;
	}
	
    /**
     * Return content blocks for specified page
     * 
     * @param string $page
     * @return array of raw text content blocks
     */
	public function get_page_content($page = null)
	{
		return $this->_recurse_content_blocks($page);
	}

	/**
	 * Return the number of pages currently in the system
	 * 
	 * @return int page count
	 */
	function get_page_count()
	{
		$count = 0;
		$do_not_include = array('__cached','__language');
		if (@$handle = opendir(CONTENT)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if (is_dir(CONTENT.DS.$file) === true && $file != "." && $file != ".." && !in_array($file,$do_not_include)) $count++;
			 }
		}
		return $count;
	}

	/**
	 * Does specified page exist?
	 * 
	 * @param string $page
	 * @return bool
	 */
	function page_exists($page = null)
	{
		if(is_dir(CONTENT.DS.$page)) return true;
		return false;
	}
	
	/**
	 * Writes raw text to specified page's content blocks
	 *
	 * @param string $page
	 * @param array $data - raw text
	 * @return bool
	 */
	function set_page_content($page = null, $data = array())
	{
		$search = array(' ');
		$replace = array('-');
		$page = strtr(trim($page), array_combine($search, $replace));

		if (@$handle = opendir(CONTENT.DS.$page.DS.'views')) 
		{
			foreach($data as $k=>$file)
			{							
				if(!file_exists(CONTENT.DS.str_replace('___',DS,$k)) || is_dir(CONTENT.DS.str_replace('___',DS,$k))) continue;
				$handle2 = fopen(CONTENT.DS.str_replace('___',DS,$k),'w');
				
				fwrite($handle2,trim(stripslashes($file)));			
				fclose($handle2); 
			 }
			 $this->clear_cache(); 
			 return true;
		}
		return false;
	}
	
	/**
	 * Set properties for specified page
	 *
	 * @param string $page
	 * @param bool $cache
	 * @param bool $protected
	 * @param int $group
	 * @return bool
	 */
	public function set_page_properties($page = null, $cache = false, $protected = true, $group = 1)     
	{
	    $config = file(CONFIG.DS.'config'.FILE_EXT);
		$search = array(' ');
		$replace = array('-');
		$page = strtr(trim($page), array_combine($search, $replace));
		$auth = get_instance(AUTH_CLASS); 
		$sys = get_instance();
		
		// definitions
		$definitions = array();
		foreach ($config as $k=>$v)
		{
			if (strpos($v,'define') !== false)
			{
				array_push($definitions,$v);
			}
		}
		$definitions = implode("",$definitions);
		
		// protected_pages
		$protected_pages = '$protected_pages = array('."\n";
		foreach ($auth->get_protected_pages() as $k=>$v)
		{
			if(!$protected && $k == $page) continue;
			if($k == $page)
			{
				$access = trim($group);
			}
			else
			{
				$access = $v;
			}			
			$entry = "\t'".$k."' => ".$access.",\n";
			$protected_pages .= $entry;
		}
		if($protected)
		{
		    if(!array_key_exists($page,$auth->get_protected_pages()))
		    {
				$access = trim($group);
		        $protected_pages .= "\t'".$page."' => {$access},\n";
		    }
		}
		$protected_pages = trim($protected_pages);
		$protected_pages = substr($protected_pages,0,-1);
		$protected_pages .= "\n".');'."\n";
		
		// do_not_cache
		$do_not_cache = '$do_not_cache = array('."\n";
		foreach ($sys->get_do_not_cache() as $entry)
		{
		    if($cache && $entry == $page) continue;
			$new_entry = "\t'".$entry."',\n";
			$do_not_cache .= $new_entry;
		}
		if(!$cache)
		{
		    if(!in_array($page,$sys->get_do_not_cache()))
		    {
		        $do_not_cache .= "\t'".$page."',\n";
		    }
		}		
		$do_not_cache = trim($do_not_cache);
		$do_not_cache = substr($do_not_cache,0,-1);
		$do_not_cache .= "\n".');'."\n";
		
		// rewrite
		ob_start();
		echo "<?php \n"; 
		echo $definitions."\n";
		echo $do_not_cache."\n";
		echo $protected_pages;
		$new_config = ob_get_contents();
		ob_end_clean();

		$handle = fopen(CONFIG.DS.'config'.FILE_EXT,'w');
		if(fwrite($handle,$new_config) === false) set_error_msg('page could not be cached');
		fclose($handle); 
	    return true;    
	}	
	
	/**
	 * Set site properties
	 * 
	 * @param array $data - properties $key=>$value
	 * @return bool
	 */
	public function set_site_properties($data = array())  
	{
		$auth = get_instance(AUTH_CLASS);
		$sys = get_instance();
		$constants = get_defined_constants(true); 
		$constants = $constants['user']; 
			
		// definitions
		$definitions = array(); 
		foreach ($data as $k=>$v)
		{
			if (array_key_exists($k,$constants))
			{
				if($v === true)
				{
					array_push($definitions,"define('{$k}',true);");
				}
				else if($v === false)
				{
					array_push($definitions,"define('{$k}',false);");
				}
				else
				{
			    	array_push($definitions,"define('{$k}','{$data[$k]}');");
				}
			}			
		}
		$definitions = implode("\n",$definitions);
		
		// protected_pages
		$protected_pages = '$protected_pages = array('."\n";
		foreach ($auth->get_protected_pages() as $k=>$v)
		{
			$entry = "\t'".$k."' => ".$v.",\n";
			$protected_pages .= $entry;
		}
		$protected_pages = trim($protected_pages);
		$protected_pages = substr($protected_pages,0,-1);
		$protected_pages .= "\n".');'."\n";
		
		// do_not_cache
		$do_not_cache = '$do_not_cache = array('."\n";
		foreach ($sys->get_do_not_cache() as $entry)
		{
			$new_entry = "\t'".$entry."',\n";
			$do_not_cache .= $new_entry;
		}		
		$do_not_cache = trim($do_not_cache);
		$do_not_cache = substr($do_not_cache,0,-1);
		$do_not_cache .= "\n".');'."\n";
		
		// rewrite
		ob_start();
		echo "<?php \n"; 
		echo $definitions."\n\n";
		echo $do_not_cache."\n";
		echo $protected_pages;
		$new_config = ob_get_contents(); 
		ob_end_clean();

		$handle = fopen(CONFIG.DS.'config'.FILE_EXT,'w');   
		if(fwrite($handle,$new_config) === false) set_error_msg('page could not be cached');
		fclose($handle); 
	    return true;  
	}
	
	/**
	 * Does specified template exist?
	 * 
	 * @param string $template
	 * @return bool
	 */
	public function template_exists($template = null)
	{
		if(is_dir($_SERVER['DOCUMENT_ROOT'].DS.TEMPLATES.DS.$template)) return true;
		return false;
	}
	
	/**
	 * Check if necessary directories are writable for admin functionality
	 * 
	 * @return boolean
	 */
	public function is_writable()
	{
	    if(! is_writable(CONTENT) || ! is_writable(ETC))
	    {
	        return false;
	    }
	    
	    return true;
	}
	
	/**
	 * Copies a page directory and frames out a controller
	 * 
	 * @param string $source
	 * @param string $dest
	 * @param string $page
	 * @return bool
	 */
	private function _copy_recursively($source = null, $dest = null, $page = null)
	{
		if (is_file($source))
		{
			if(copy($source,$dest))
			{
				$handle = fopen($dest,'w');
				$content = "<?php if(!defined('SYSTEM')) die('no access');  ?>\n<USE_DEFAULT/>";
				if(strpos($dest,'index'.FILE_EXT) !== false)
				{
					$content = "<?php if(!defined('SYSTEM')) die('no access');  \n"
							  ."class Page_{$page} {\n"
							  ."}";
				}
				fwrite($handle,$content);			
				fclose($handle); 
				return true; 
			}
			return false;
		}
		
		if (!is_dir($dest))
		{
			mkdir($dest);
		}
		
		if (@$handle = opendir($source)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if($file == '.' || $file == '..') continue;
				$this->_copy_recursively($source.DS.$file,$dest.DS.$file,$page);
			}
		}
		return true;
	} 
	
	/**
	 * Deletes a directory and all its contents
	 * 
	 * @param string $path
	 * @return bool
	 */
	private function _delete_dir($path = null)
	{
        if(is_file($path)){
            return @unlink($path);
        }
        elseif(is_dir($path)){
            $scan = glob(rtrim($path,DS).DS.'*');
            foreach($scan as $k=>$v){
                $this->_delete_dir($v);
            }
            return @rmdir($path);
        }
    }	
	
    /**
     * Return content blocks for specified page
     * 
     * @param string $page
     * @return array of raw text content blocks
     */
	private function _recurse_content_blocks($page = null)
	{
		$blocks = array();
		if (@$handle = opendir(CONTENT.DS.$page)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..")  
				{
					if(is_dir(CONTENT.DS.$page.DS.$file) !== false)
					{
						$blocks[$file] = $this->_recurse_content_blocks($page.DS.$file);
					}
					else
					{
						$blocks[str_replace(DS,'___',$page).'___'.$file] = file_get_contents(CONTENT.DS.$page.DS.$file);
					}
				}
			 }
		}
		return $blocks;
	}
}