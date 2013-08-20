<?php if(!defined('SYSTEM')) die('no access'); ?>
<ul class="jqueryFileTree" style="display: none;">
<?php foreach($file_tree as $k=>$v): ?>
<?php if(is_array($v)): ?>
<li class="directory collapsed"><a href="#" rel="<?php echo htmlentities($_POST['dir'] . '/' . $k); ?>/" ><?php echo $k; ?></a></li>
<?php else: ?>
<li class="file ext_php"><a href="#" onclick="popup('<?php echo $v; ?>');return false;" ><?php echo $v; ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>