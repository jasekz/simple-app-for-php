<?php if(!defined('SYSTEM')) die('no access'); ?>

<form action="<?php echo page('admin-edit-page'); ?>" method="post" />
<TEMPLATE block="main_content">
<b><?php echo EDIT; ?> <i><?php echo ucfirst($_GET['edit_page']).EXT; ?></i></b>
<a href="<?php echo page($_GET['edit_page']); ?>" target="_blank" class="preview" title="<?php echo PREVIEW_PAGE; ?>"><?php echo PREVIEW_PAGE; ?></a>
<input style="float:right;" type="submit" value="<?php echo SAVE; ?>" />
</TEMPLATE>

<TEMPLATE block="main_content">
    <table cellpadding="2" cellspacing="2" width="100%" class="table-form">
        <thead>
        <tr>
            <th class="table-form-title" colspan="2"><b><?php echo PROPERTIES; ?></b></th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="table-form-label" style="width:100px;"><?php echo CACHING; ?>: </td>
                <td class="table-form-field">
                    <input type="checkbox" name="cache" <?php if(!$do_not_cache) echo 'checked="checked"'; ?>/>  
                    <?php echo CACHE_PAGE; ?>
                </td>
            </tr>
            <tr>
                <td class="table-form-label"><?php echo SECURE_LOGIN; ?>: </td>
                <td class="table-form-field">
                    <input type="checkbox" name="protect" <?php if($protected) echo 'checked="checked"'; ?>/> 
                    <?php echo PROTECT; ?>
                    <select name="user_access_level" style="margin-left:15px;" >
                        <?php foreach($user_access_levels as $k=>$v):?>
                        <option value="<?php echo $k; ?>" <?php echo $k == $access_level ? 'selected="selected"' : ''; ?> >
                            <?php echo $v; ?>
                         </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo ACCESS_LEVEL; ?>
                </td>
            </tr>
        </tbody>
    </table>
</TEMPLATE>
<TEMPLATE block="main_content">
    <table cellpadding="2" cellspacing="2" width="100%" class="table-data">
        <thead>
        <tr>
            <th class="table-form-title"><b><?php echo CONTENT_BLOCKS; ?></b></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
            <div id="xpander" style="width:590px;">
            <ul>
            <?php 
			recurse_array($blocks);
			function recurse_array($blocks)
			{
				foreach($blocks as $k=>$v)
				{
					if(is_array($v))
					{
						echo '<li>'
							.'<div  class="row">'
						    .'<img src="'.TEMPLATE_PATH.'/images/folder_open.png" />'														
						    .'<a href="#" class="open toggle" >'.$k
							.'<div class="controls">'
							//.'<a href="#"><img width="10" src="'.TEMPLATE_PATH.'/images/add.png" />'.ADD_FOLDER.'</a>'
							//.($k == 'views' ? '' : '<a href="#"><img width="10" src="'.TEMPLATE_PATH.'/images/delete.png" />'.DELETE_FOLDER.'</a>')
							//.'<a href="#"><img width="10" src="'.TEMPLATE_PATH.'/images/add.png" />'.ADD_FILE.'</a>'
							.'</div>'
							.'</a>'
							.'</div>'
						    .'<ul>';
						recurse_array($v);
						echo '</ul></li>';
					}
					else
					{
						echo '<li>'
							.'<div  class="row">'
							.'<img src="'.TEMPLATE_PATH.'/images/'.substr($k,-3).'.png" />'
						    .'<input type="submit" value="'.SAVE.'" />'
						    .'<a href="#" class="edit closed" title="'.EDIT.'">'
						    .end(explode('___',$k))
						    .'</a>'		
							.'<a href="#" class="controls">'
							//.'<img width="10" src="'.TEMPLATE_PATH.'/images/delete.png" />'.DELETE_FILE
							.'</a>'				    
						    .'<textarea name="block['.$k.']" class="textarea">'
						    .htmlentities($v)
						    .'</textarea>'	
							.'</div>'					    
						    .'</li>';
					}
				}
			}
			?>
            </ul>
            </div>
            </td>
        </tr>
        </tbody>
     </table>
    <input type="hidden" name="<?php echo CMD; ?>" value="edit_page" />
    <input type="hidden" name="page" value="<?php echo $_GET['edit_page']; ?>" />
</TEMPLATE>
</form>