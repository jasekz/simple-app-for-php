<TEMPLATE block="main_content">
<b><?php echo DELETE_USER; ?></b>
</TEMPLATE>

<TEMPLATE block="main_content">
<?php echo str_replace('<VALUE/>',$user->first_name.' '.$user->last_name,DELETE_USER_CONFIRM); ?>
<form action="<?php echo page('admin-delete-user'); ?>" method="post" >
	<input type="submit" value="<?php echo SUBMIT; ?>" />
    <a href="<?php echo page('admin-users'); ?>" title="<?php echo CANCEL; ?>" ><?php echo CANCEL; ?></a>
    <input type="hidden" name="<?php echo CMD; ?>" value="delete_user" />
    <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>" />
  	<input type="hidden" name="delete_confirmed" value="<?php echo $_SESSION['delete_confirmed']; ?>" />
</form>
</TEMPLATE>