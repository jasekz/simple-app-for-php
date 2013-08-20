####### system constants #######
SYSTEM
BASE_URL
BASE_SEGMENT
DEBUG_LOG
TEMPLATE
ADMIN_TEMPLATE
CURRENT_TEMPLATE
SUFFIX
CACHE
SEF_URLS
AUTH_CLASS

####### helper functions #######
do_include($page, $file) - ex: do_include('test','test.php') == include('/home/.../simpleappdir/content/test/test.php')
dump($msg) - var_dump() but more readable
get_instance($class = 'sys') - returns instance of class (singleton)
load_custom($file,$data,$page)
log_msg($msg,$append) - writes $msg to debug file (DEBUG_LOG)
msg() 
page($page,$query_str) - ex: page('test','var1=hello&var2=world') == http://mysite.com/appdir/test.html?var1=hello&var2=world (sef)
						 or http://mysite.com/appdir/index.php?page=test&var1=hello&var2=world  (no sef)
pagination($total_rows,$limit,$status) - returns pagination block
redirect($page,$query_str) - ex: redirect('test','var1=hello&var2=world') == 
							    header('location: http://mysite.com/appdir/test.html?var1=hello&var2=world') (sef)
							 or header('location: http://mysite.com/appdir/index.php?page=test&var1=hello&var2=world') (no sef)
set_error_msg($msg) - set error msg
set_msg($msg) - set success msg

####### system functions #######

####### directives #######
<USE_DEFAULT/>
<TEMPLATE block="block_name"></TEMPLATE>
<CONTENT/>
<USER/> - these can be anything as long as the file name in page dirs corresponds
<DEFINED/> - ex: /test/defined.php will replace <DEFINED/> in template
<BLOCKS/> - ex: /test/blocks.php will replace <BLOCKS/> in template

####### config #######
$do_not_cache [array] - page not to be cached
$protected [array] - secure login pages

####### directories #######
cached - cached pages
classes - user classes; can be loaded using get_instance('class_name') singleton loader; 
		  must follow naming convention: my_class_name.class.php & class my_class_name {}
config - config.php & constants.php
content - pages; each directory is a page; directories beginning with '__' are system files and must be present;
		  files inside each directory is a secion in the template and must follow naming convention,
		  for example: contents in footer.php will replace <FOOTER/> in template;
		  if index.php is present in a page directory, it will be loaded before all other files so it can
		  be used as a controller; more details in section 'pages'
etc - can be used for anything; not used by system
templates - each directory corresponds to the template name as specified in TEMPLATE and ADMIN_TEMPLATE;
		    index.php is the default file which will be called but this can be changed by setting the variable
		    $template in the page's controller (index.php);  
		    for example, if you want to use layout secondary.php instead of the default index.php in the template
		    'simpleapp' for the page test.html (or ?page=test), set $template='secondary.php' in /content/test/index.php 