<?php if(!defined('SYSTEM')) die('no access'); ?> 

<TEMPLATE block="main_content">
<b><?php echo USERS_GROUPS; ?></b>
</TEMPLATE>

<TEMPLATE block="main_content">
    <table cellpadding="2" cellspacing="2" width="100%" class="table-data">
        <thead>
        <tr>
            <th colspan="5" class="table-form-title"><b><?php echo USERS; ?></b></th>
        </tr>
        <tr class="table-header">
            <th><?php echo ID; ?></th>
            <th><?php echo EMAIL; ?></th>
            <th align="center"><?php echo ACCESS_LEVEL; ?></th>
            <th align="center"><?php echo EDIT; ?></th>
            <th align="center"><?php echo DELETE; ?></th>
        </tr>
        </thead>
        <tbody>
            
        <?php if(!empty($users)):foreach($users as $user): ?>
        <tr>
            <td><?php echo $user->user_id;?></td>
            <td><?php echo $user->email; ?></td>
            <td><?php echo $user->name; ?></td>
            <td align="center"><a href="<?php echo page('admin-edit-user','user_id='.$user->user_id); ?>" title="<?php echo EDIT; ?>"><div class="icon-edit"></div></a></td>
            <td align="center"><a href="<?php echo page('admin-delete-user','user_id='.$user->user_id); ?>" title="<?php echo DELETE; ?>"><div class="icon-delete"></div></a></td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
    <?php echo $pagination; ?>
</TEMPLATE>