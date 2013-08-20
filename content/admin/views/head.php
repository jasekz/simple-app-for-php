<?php if(!defined('SYSTEM')) die('no access');
$sys = get_instance();
$page = $sys->get_page();
?>
<style>
#sideNav li a#<?php echo $page;?>, #main-menu li a#<?php echo $page;?>{
	color:#8f7731;
	font-weight:bold;
}
</style>