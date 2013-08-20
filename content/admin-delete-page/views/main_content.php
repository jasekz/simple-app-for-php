<TEMPLATE block="main_content">
<b><?php echo DELETE_PAGE; ?></b>
</TEMPLATE>

<TEMPLATE block="main_content">
<?php echo str_replace('<VALUE/>',$_GET['delete_page'],DELETE_PAGE_CONFIRM); ?>
<form action="<?php echo page('admin-delete-page'); ?>" method="post" >
	<input type="submit" value="<?php echo SUBMIT; ?>" />
    <a href="<?php echo page('admin-pages'); ?>" title="<?php echo CANCEL; ?>" ><?php echo CANCEL; ?></a>
    <input type="hidden" name="<?php echo CMD; ?>" value="delete_page" />
    <input type="hidden" name="page" value="<?php echo $_GET['delete_page']; ?>" />
  	<input type="hidden" name="delete_confirmed" value="<?php echo $_SESSION['delete_confirmed']; ?>" />
</form>
</TEMPLATE>