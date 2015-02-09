<?php if(!defined('SYSTEM')) die('no access');  ?>
<?php 
$admin = get_instance('admin');
if( ! $admin->is_writable()): ?>
<div class="msg-error">
Your <b>content</b> and <b>etc</b> directories must be writeable for proper functionality.
<br>
<b>WARNING: </b> If these are in you web root directory, it is NOT SAFE to make them writable.<br>
Please see <a href="http://simpleapp.info/documentation/examples/setup.html" target="_blank" >Setting up and securing an app</a> 
if you are unsure how to do this.
</div>
<?php endif; ?>

<h1 id="logo" >SimpleApp Open source application development framework for PHP 5</h1>

