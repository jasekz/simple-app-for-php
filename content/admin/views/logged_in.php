<?php if(!defined('SYSTEM')) die('no access');  ?>
<div id="logged_in">
<?php echo LOGGED_IN_AS; ?>: <?php echo isset($_SESSION['session']['user']) ? $_SESSION['session']['user'] : ''; ?>&nbsp;&nbsp;
<a href="<?php echo page('admin',CMD.'=logout&'.REDIRECT.'=admin'); ?>"><?php echo LOGOUT;?></a>
</div>