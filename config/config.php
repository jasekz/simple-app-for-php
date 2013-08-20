<?php 
define('DEBUG_LOG','debug.html');
define('TEMPLATE','simpleapp');
define('ADMIN_TEMPLATE','simpleapp');
define('EXT','.html');
define('CACHE',false);
define('SEF_URLS',false);
define('AUTH_CLASS','simpleauth');
define('LOGIN_PAGE','login');
define('ADMIN_LOGIN_PAGE','admin-login');
define('DEFAULT_LANGUAGE','english');

$do_not_cache = array(
	'__404',
	'admin-configuration',
	'admin-create-page',
	'admin-delete-page',
	'admin-edit-page',
	'admin-pages',
	'admin-users',
	'login',
	'admin',
	'admin-create-user',
	'admin-edit-user',
	'admin-delete-user',
	'admin-login',
	'home',
	'__default'
);

$protected_pages = array(
	'admin-edit-page' => 1,
	'admin-delete-page' => 1,
	'admin-users' => 1,
	'admin-all-pages' => 1,
	'admin-create-page' => 1,
	'admin-configuration' => 1,
	'admin-create-user' => 1,
	'admin-delete-user' => 1,
	'admin' => 1,
	'admin-edit-user' => 1,
	'admin-pages' => 1
);
