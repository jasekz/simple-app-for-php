<?php if(!defined('SYSTEM')) die('no access'); ?>
<TEMPLATE block="sidebar">
<div class="menu-header"><?php echo PAGES; ?></div> 
<?php do_include('admin','menu_pages'); ?>
<div class="menu-header"><?php echo USERS; ?></div>
<?php do_include('admin','menu_users'); ?>
<div class="menu-header"><?php echo UTILITIES; ?></div>
<?php do_include('admin','menu_utilities'); ?>
</TEMPLATE>
<TEMPLATE block="sidebar">
<div class="menu-header"><?php echo HELP; ?></div> 
<a href="http://simpleapp.info/documentation.html" target="_blank" ><?php echo DOCUMENTAION; ?></a>
</TEMPLATE>