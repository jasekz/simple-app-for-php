<?php if(!defined('SYSTEM')) die('no access');  ?>

<TEMPLATE block="main_content">
<b><?php echo EDIT_USER; ?></b>
</TEMPLATE>

<TEMPLATE block="main_content">
<form action="<?php echo page('admin-edit-user','user_id='.$user->user_id); ?>" method="post"> 
	<table cellpadding="2" cellspacing="2" width="100%" class="table-form">
    	<thead>
		<tr>
			<th colspan="2" class="table-form-title"><b>Page Properties</b></th>
		</tr>
        </thead>
        <tbody>
		<tr>
			<td class="table-form-label" width="150">First Name: </td>
			<td class="table-form-field"><input type="text" name="first_name" value="<?php echo isset($first_name) ? $first_name : (isset($user->first_name) ? $user->first_name : ''); ?>" /></td>
		</tr>
		<tr>
			<td class="table-form-label">Last Name: </td>
			<td class="table-form-field"><input type="text" name="last_name" value="<?php echo isset($user->last_name) ? $user->last_name : (isset($last_name) ? $last_name : ''); ?>" /></td>
		</tr>
		<tr>
			<td class="table-form-label">Email: </td>
			<td class="table-form-field"><input type="text" name="email" value="<?php echo isset($user->email) ? $user->email : (isset($email) ? $email : ''); ?>" /></td>
		</tr>
		<tr>
			<td class="table-form-label">Password: </td>
			<td class="table-form-field"><input type="password" name="password[]" /></td>
		</tr>
		<tr>
			<td class="table-form-label">Re-enter Password: </td>
			<td class="table-form-field"><input type="password" name="password[]" /></td>
		</tr>
		<tr>
			<td class="table-form-label">access_level: </td>
			<td class="table-form-field">
            	<select name="user_access_level" style="margin-left:15px;" >
				<?php foreach($user_access_levels as $k=>$v):?>
                <option value="<?php echo $k; ?>" <?php echo isset($user->access_level) && $user->access_level == $k ? 'selected="selected"' : (isset($user_access_level) && $user_access_level == $k ? 'selected="selected"' : ''); ?>><?php echo $v; ?></option>
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
    <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
    <input type="hidden" name="<?php echo CMD; ?>" value="edit_user" /> 
</form>   
</TEMPLATE>