<?php if(!defined('SYSTEM')) die('no access'); ?>
<ul class="menu">
  <li>
    <a href="<?php echo page('admin-pages'); ?>" id="admin-pages" title="<?php echo VIEW_PAGES; ?>"><?php echo VIEW_PAGES; ?></a>
  </li>
  <li>
    <a href="<?php echo page('admin-create-page'); ?>" id="admin-create-page" title="<?php echo CREATE_PAGE; ?>"><?php echo CREATE_PAGE; ?></a>
  </li>
</ul>