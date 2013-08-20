<?php if(!defined('SYSTEM')) die('no access');  
$sys = get_instance();
if($sys->is_member_page()): ?>
<div style="position:absolute;top:5px;right:5px;padding:5px;border:#999 solid 1px;">
    Logged in as: <?php echo isset($_SESSION['session']['user']) ? $_SESSION['session']['user'] : ''; ?>&nbsp;&nbsp;
    <a href="<?php echo page('home',CMD.'=logout&'.REDIRECT.'=home'); ?>">Logout</a>
</div>
<?php endif; ?>