<?php if(!defined('SYSTEM')) die('no access'); ?> 

<TEMPLATE block="main_content">
<b><?php echo MEMBER_LOGIN_TXT; ?></b>
</TEMPLATE>

<TEMPLATE block="main_content">
<div style="margin:30px;">
<form name="login" action="<?php echo page('login'); ?>" method="post">
	<input type="text" name="email" />
    <input type="password" name="password" />
    <input type="submit" value="Submit"/>
    <input type="hidden" name="success" value="<?php echo isset($_GET[REDIRECT]) ? $_GET[REDIRECT] : 'home-page'; ?>" /> 
    <input type="hidden" name="failure" value="<?php echo isset($_GET[REDIRECT]) ? $_GET[REDIRECT] : 'home-page'; ?>" />
    <input type="hidden" name="<?php echo CMD; ?>" value="login" />
</form>
</div>
</TEMPLATE>