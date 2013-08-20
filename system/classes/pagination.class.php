<?php if(!defined('SYSTEM')) die('no access'); 
/**
 * Pagination class  
 *
 * @package 	 SimpleApp    
 * @author 		 Zbigniew Jasek 
 * @copyright	 Copyright (c) 2012, Zbigniew Jasek
 * @version 	 1.0
 * @link 		 http://simpleapp.info   
 * @license 	 http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class Pagination {
    
	/**
	 * Base url, i.e. http://simpleapp.info
	 * @var string
	 */
    private  $base_url;
    
    /**
     * Total number of entries for all pages
     * @var int
     */
    private  $total_rows;
    
    /**
     * Total number of page links that will be displayed
     * @var int
     */
    private $total_page_links;
    
    /**
     * Maximum number of entries per page
     * @var int
     */
    private $limit;
    
    /**
     * Starting page
     * @var int
     */   
    private $current_start;
    
    /**
     * Previous page
     * @var int
     */
    private $previous_start;   

    /**
     * Current page number
     * @var int
     */
    private $current_page_link;     
    
    /**
     * Constructor
     */
	public function __construct()  
	{
	} 
	
	/**
	 * Main pagination interface
	 * 
	 * @param int $total_rows
	 * @param int $limit
	 * @param int $max_page_links
	 * @param bool $status_bar
	 * @return string pagination html code
	 */
	public function paginate($total_rows = 0, $limit = 5, $max_page_links = 5, $status_bar = true) 
	{
	    $chunks = explode('?',$_SERVER['REQUEST_URI']);
	    $this->base_url = $chunks[0];
	    $this->total_rows = $total_rows;
	    $this->limit = $limit;
	    $this->total_page_links = ceil($total_rows/$limit);	    
	    $this->previous_start = '';
	    $this->current_start = isset($_GET['start']) ? $_GET['start'] : null;
		$this->max_page_links = $max_page_links;
		$this->current_page_link = $this->current_start ? ceil($this->current_start/$this->limit)+1 : 1;
		$this->full_sets = floor($this->total_page_links/$this->max_page_links); 
		$this->partial_set = $this->total_page_links%$this->max_page_links == 0 ? false : true;
		$this->this_set = ceil($this->current_page_link/$this->max_page_links);

    	$pagination_open = '<div class="pagination">';
    	$pagination_close = '</div>';
		
		$first = '';
		if($this->this_set > 1)
		{
			$first = $this->_get_first_page_link();
		}
		
		$last = '';
		if($this->full_sets > 1 && $this->this_set <= $this->full_sets)
		{
			$skip = $this->full_sets == $this->this_set && !$this->partial_set;
			if(!$skip)
			{
				$last = $this->_get_last_page_link();
			}
		}
	    
	    $page_links = $this->total_page_links > 1 ? $this->_get_page_links() : '';
	    $status_bar = $status_bar ? $this->_get_status_bar() : '';
	    $previous_link = $this->_get_previous_page_link();
	    $next_link = $this->_get_next_page_link();
	    return $pagination_open
			  .$first
	          .$previous_link
	          .$page_links
	          .$next_link
			  .$last
	          .$status_bar
	          .$pagination_close; 
	}
	
	/**
	 * First page link html code
	 * 
	 * @return string html code
	 */
	private function _get_first_page_link()
	{
	    $first = '<div class="pagination_first"><a href="'.$this->base_url.'">&lt;&lt;</a></div>';
		return $first;
	}
	
	/**
	 * Last page link html code
	 * 
	 * @return string html code
	 */
	private function _get_last_page_link()
	{
		$start = $this->total_page_links*$this->limit-$this->limit;
	    $first = '<div class="pagination_last"><a href="'.$this->base_url.'?start='.$start.'">&gt;&gt;</a></div>';
		return $first;
	}
	
	/**
	 * 'Next' page link html code
	 * 
	 * @return string html code
	 */
	private function _get_next_page_link()
	{
	    if($this->current_start + $this->limit >= $this->total_rows) return '';	    
	    $num = $this->current_start+$this->limit;
    	$start = '?start='.$num; 
    	$query_str_arr = array();
    	$query_str = '';
	    foreach($_GET as $k=>$v)
    	{
    	    if($k == 'start') continue; // if start=0, we'll leave it out of the url
    		array_push($query_str_arr, $k.'='.$v);
    	}
    	if(!empty($query_str_arr))
    	{
    	    $query_str = '&'.implode('&',$query_str_arr);
    	}
    	$next = '<div class="pagination_next"><a href="'.$this->base_url.$start.$query_str.'">></a></div>';
    	return $next; 
	}
	
	/**
	 * Numbered page links
	 * 
	 * @return string html code
	 */
	private function _get_page_links()
	{ 
	    $page_links = '';
		$to = $this->this_set*$this->max_page_links;
		$from = $to-$this->max_page_links+1;
		if($from < 1) $from = 1;
		if($to > $this->total_page_links) $to = $this->total_page_links;
	    
    	for($i=$from;$i<=$to;$i++)
    	{
    		$query_str_arr = array();
    		$query_str = '';
    		$start = '';
    		$current = '';
    		if($i != 1) // if start=0, we'll leave it out of the url
    		{ 
    			$start = '?start='.($i-1)*$this->limit;
    		}		
    		if(!$this->current_start && $i == 1) // if start is not in the url query string
    		{
    			$current = 'pagination_current';
    		}
    		else if($this->current_start == ($i-1)*$this->limit) // current page
    		{
    			$current = 'pagination_current';
    		}		
    
    		foreach($_GET as $k=>$v)
    		{
    			if($k == 'start') continue; // if start=0, we'll leave it out of the url
    			array_push($query_str_arr, $k.'='.$v);
    		}
    		
    		if(!empty($query_str_arr)) // re-build query string
    		{
    			$query_str = ($i == 1 ? '?' :'&').implode('&',$query_str_arr);
    		}		
    		$page_links .= '<div class="pagination_page '.$current.'" id="pagination_page_'.$i.'">'				
    				      .'<a href="'.$this->base_url.$start.$query_str.'">'.$i.'</a>'
    				      .'</div>';
    	}
    	return $page_links;
	}
	
	/**
	 * 'Previous' page link html code
	 * 
	 * @return string html code
	 */
	private function _get_previous_page_link()
	{
	    if(!$this->current_start) return '';	    
	    $num = $this->current_start-$this->limit;
    	$start = $num == 0 ? '' : '?start='.$num; 
	    $query_str_arr = array();
    	$query_str = '';
	    foreach($_GET as $k=>$v)
    	{
    	    if($k == 'start') continue; // if start=0, we'll leave it out of the url
    		array_push($query_str_arr, $k.'='.$v);
    	}
    	if(!empty($query_str_arr))
    	{
    	    $query_str = ($num == 0 ? '?' :'&').implode('&',$query_str_arr);
    	}
    	$prev = '<div class="pagination_previous"><a href="'.$this->base_url.$start.$query_str.'"><</a></div>';
    	return $prev; 
	}
	
	/**
	 * Page status html code
	 * 
	 * @return string html code
	 */
	private function _get_status_bar()
	{
    	$from = $this->current_start ? $this->current_start+1 : '1';
    	if($from  + $this->limit <= $this->total_rows)
    	{
    	    $to = $from + $this->limit - 1;
    	}
    	else 
    	{
    	    $to = $this->total_rows;
    	}
		if($this->total_rows == 0)
		{
			$from = $to = 0;
		}
    	$txt = str_replace('<FROM>',$from,PAGINATION_SHOWING);
    	$txt = str_replace('<TO>',$to,$txt);
    	$txt = str_replace('<TOTAL>',$this->total_rows,$txt);
    	$status = '<div class="pagination_showing">'.$txt.'</div>';
    	return $status;
	}
}