<?php if(!defined('SYSTEM')) die('no access'); ?>
<TEMPLATE block="main_content">
<b><?php echo PAGES; ?></b>
</TEMPLATE>
<TEMPLATE block="main_content">
    <table cellpadding="2" cellspacing="2" width="100%" class="table-data">
        <thead>
        <tr>
            <th colspan="4" class="table-form-title"><b><?php echo PAGES; ?></b></th>
        </tr>
        <tr class="table-header">
            <th><?php echo PAGE_TXT; ?></th>
            <th align="center"><?php echo VIEW; ?></th>
            <th align="center"><?php echo EDIT; ?></th>
            <th align="center"><?php echo DELETE; ?></th>
        </tr>
        </thead>
        <tbody>
            
        <?php foreach($all_pages as $page): ?>
        <tr>
            <td>
            <?php 
            if(array_key_exists($page,$protected_pages)) echo '<div class="icon-lock"></div>';
            if(!in_array($page,$do_not_cache)) echo '<div class="icon-favorite"></div>';
            echo $page; 
            ?>
            </td>
            <td align="center">
            <?php if(substr($page,0,2) != '__'): ?>
            <a target="_blank" href="<?php echo page($page); ?>" title="<?php echo PREVIEW_PAGE; ?>"><div class="icon-search"></div></a>
            <?php endif; ?>
            </td>
            <td align="center"><a href="<?php echo page('admin-edit-page','edit_page='.$page); ?>" title="<?php echo EDIT; ?>"><div class="icon-edit"></div></a></td>
            <td align="center">
            <?php if(substr($page,0,2) != '__'): ?>
            <a href="<?php echo page('admin-delete-page','delete_page='.$page); ?>" title="<?php echo DELETE; ?>"><div class="icon-delete"></div></a>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $pagination; ?>
</TEMPLATE>