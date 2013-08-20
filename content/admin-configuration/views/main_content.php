<?php if(!defined('SYSTEM')) die('no access');  ?>
<TEMPLATE block="main_content">
<b><?php echo CONFIGURATION; ?></b>
</TEMPLATE>
<TEMPLATE block="main_content">
<form action="<?php echo page('admin-configuration'); ?>" method="post"> 
  <table cellpadding="2" cellspacing="2" width="100%" class="table-form">
  <thead>
    <tr>
      <th colspan="2" class="table-form-title"><b><?php echo CONFIGURATION; ?></b></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="table-form-label" width="200"><?php echo TPL; ?>: </td>
      <td class="table-form-field"><input type="text" name="template" value="<?php echo TEMPLATE; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo ADMIN_TPL ?>: </td>
      <td class="table-form-field"><input type="text" name="admin_template" value="<?php echo ADMIN_TEMPLATE; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo PAGE_EXT; ?>: </td>
      <td class="table-form-field"><input type="text" name="suffix" value="<?php echo EXT; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo LOGIN_PG; ?>: </td>
      <td class="table-form-field"><input type="text" name="login_page" value="<?php echo LOGIN_PAGE; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo ADMIN_LOGIN_PG; ?>: </td>
      <td class="table-form-field"><input type="text" name="admin_login_page" value="<?php echo ADMIN_LOGIN_PAGE; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo AUTH_CLS; ?>: </td>
      <td class="table-form-field"><input type="text" name="auth_class" value="<?php echo AUTH_CLASS; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo DEBUG_LG; ?>: </td>
      <td class="table-form-field"><input type="text" name="debug_log" value="<?php echo DEBUG_LOG; ?>" /></td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo LANGUAGE_TXT; ?>: </td>
      <td class="table-form-field">
      
      <select name="language">
      <?php foreach($languages as $k=>$v): ?>
      <option value="<?php echo $v; ?>" <?php echo $v == DEFAULT_LANGUAGE ? 'selected="selected"' : ''; ?>><?php echo ucwords($v); ?></option>
      <?php endforeach; ?>
      </select>
      </td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo CACHING; ?>:</td>
      <td class="table-form-field">
        <input type="radio" name="cache" value="true" <?php echo  CACHE ? 'checked="checked"' : ''; ?>> <?php echo CACHE_PAGES; ?><br/>
        <input type="radio" name="cache" value="false" <?php echo  CACHE ? '' : 'checked="checked"'; ?>> <?php echo DO_NOT_CACHE; ?>
      </td>
    </tr>
    <tr>
      <td class="table-form-label"><?php echo SEO_URL; ?>:</td>
      <td class="table-form-field">
        <input type="radio" name="sef_urls" value="true" checked="checked"> <?php echo SEO_URL; ?><br/>
        <input type="radio" name="sef_urls" value="false" <?php echo  SEF_URLS ? '' : 'checked="checked"'; ?>> <?php echo QUERY_STR; ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="<?php echo SUBMIT; ?>" /></td>
    </tr>
  </tbody>
  </table>
  <input type="hidden" name="<?php echo CMD; ?>" value="edit" />
</form>
</TEMPLATE>