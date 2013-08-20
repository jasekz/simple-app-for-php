<?php if(!defined('SYSTEM')) die('no access');
$url = urlencode(get_current_url());
?>
<ul class="menu">
  <li><a href="<?php echo page('admin-configuration'); ?>" id="admin-configuration" title="<?php echo CONFIGURATION; ?>"><?php echo CONFIGURATION; ?></a></li>
  <li><a href="<?php echo page('admin',CMD.'=clear_cache&'.REDIRECT.'='.$url); ?>" id="clear" title="<?php echo CLEAR_CACHE; ?>"><?php echo CLEAR_CACHE; ?></a></li>
  <li><a href="<?php echo page('admin',CMD.'=logout&'.REDIRECT.'=admin'); ?>" id="logout" title="<?php echo LOGOUT; ?>"><?php echo LOGOUT; ?></a></li>   
</ul>