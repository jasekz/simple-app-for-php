<?php if(!defined('SYSTEM')) die('no access');  ?>

<TEMPLATE block="main_content">
<b><?php echo CREATE_USER; ?></b>
</TEMPLATE>

<TEMPLATE block="main_content">
<form action="<?php echo page('admin-create-user'); ?>" method="post"> 
	<table cellpadding="2" cellspacing="2" width="100%" class="table-form">
    	<thead>
		<tr>
			<th colspan="2" class="table-form-title"><b><?php echo PROPERTIES; ?></b></th>
		</tr>
        </thead>
        <tbody>
		<tr>
			<td class="table-form-label" width="150"><?php echo FIRST_NAME; ?>: </td>
			<td class="table-form-field"><input type="text" name="first_name" value="<?php echo isset($first_name) ? $first_name : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo LAST_NAME; ?>: </td>
			<td class="table-form-field"><input type="text" name="last_name" value="<?php echo isset($last_name) ? $last_name : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo EMAIL; ?>: </td>
			<td class="table-form-field"><input type="text" name="email" value="<?php echo isset($email) ? $email : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo PASSWORD; ?>: </td>
			<td class="table-form-field"><input type="password" name="password[]" value="" /></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo PASSWORD2; ?>: </td>
			<td class="table-form-field"><input type="password" name="password[]" value="" /></td>
		</tr>
		<tr>
			<td class="table-form-label"><?php echo ACCESS_LEVEL; ?>: </td>
			<td class="table-form-field">
            	<select name="user_access_level" style="margin-left:15px;" >
				<?php foreach($user_access_levels as $k=>$v):?>
                <option value="<?php echo $k; ?>" <?php echo isset($user_access_level) && $user_access_level == $k ? 'selected="selected"' : ''; ?>><?php echo $v; ?></option>
                <?php endforeach; ?>
                </select>
            </td>
		</tr>
        <tr>
        	<td>&nbsp;</td>
        	<td><input type="submit" value="<?php echo SUBMIT; ?>" /></td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="<?php echo CMD; ?>" value="create_user" /> 
</form>   
</TEMPLATE>