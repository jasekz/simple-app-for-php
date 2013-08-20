<?php if(!defined('SYSTEM')) die('no access');  ?>
<TEMPLATE block="main_content">
<b><?php echo CREATE_PAGE; ?></b>
</TEMPLATE>
<TEMPLATE block="main_content">
<form action="<?php echo page('admin-create-page'); ?>" method="post"> 
	<table cellpadding="2" cellspacing="2" width="100%" class="table-form">
    	<thead>
		<tr>
			<th colspan="2" class="table-form-title"><b><?php echo PROPERTIES; ?>:</b></th>
		</tr>
        </thead>
        <tbody>
		<tr>
			<td class="table-form-label"><?php echo TITLE; ?>: </td>
			<td class="table-form-field"><input type="text" name="page_title" value="" /></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo CACHING; ?>:</td>
			<td class="table-form-field"><input type="checkbox" name="cache" /> <?php echo CACHE_PAGE; ?></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo ACCESS; ?>:</td>
            <td class="table-form-field">
                	<input type="checkbox" name="protect" /> <?php echo SECURE_LOGIN; ?>
                	<select name="user_access_level" style="margin-left:15px;" >
						<?php foreach($user_access_levels as $k=>$v):?>
                        <option value="<?php echo $k; ?>" >
                            <?php echo $v; ?>
                         </option>
                        <?php endforeach; ?>
                	</select>
                	<?php echo ACCESS_LEVEL; ?>
             </td>
		</tr>
        <tr>
        	<td>&nbsp;</td>
        	<td><input type="submit" value="<?php echo SUBMIT; ?>" /></td>
        </tr>
        </tbody>
    </table>    
</TEMPLATE>
<input type="hidden" name="<?php echo CMD; ?>" value="create_page" />
</form>
<script type="text/javascript">  
var slider1=new accordion.slider("slider1");
slider1.init("slider"); 
</script>