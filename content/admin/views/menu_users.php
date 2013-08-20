<?php if(!defined('SYSTEM')) die('no access'); 
$auth = get_instance(AUTH_CLASS);
$user_count = $auth->get_user_count();
?>
<ul class="menu">
  <li>
    <a href="<?php echo page('admin-users'); ?>" id="admin-users" title="<?php echo VIEW_USERS; ?>" >
      <?php echo VIEW_USERS; ?> <span style="margin-left:15px;font-style:italic;"></span>
    </a>
  </li>
  <li>
    <a href="<?php echo page('admin-create-user'); ?>" id="admin-create-user" title="<?php echo VIEW_USERS; ?>" >
      <?php echo CREATE_USER; ?>
    </a>
  </li>
</ul>