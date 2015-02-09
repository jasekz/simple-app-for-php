<?php if(!defined('SYSTEM')) die('no access');  ?>
<h1 id="logo" >SimpleApp Open source application development framework for PHP 5</h1>
<ul id="main-menu">
	<li><a href="<?php echo page('admin'); ?>" target="_blank" >Admin</a></li>
    <li><a href="http://simpleapp.info/documentation.html" target="_blank" >Documentation</a></li>
    <li><a href="https://github.com/jasekz/simple-app-for-php" target="_blank" >Downloads</a></li>
    <li><a href="<?php echo page(''); ?>" <?php if($this->page == 'home' || $this->page == '__default') echo 'class="selected"'; ?>>Home</a></li>    
</ul>