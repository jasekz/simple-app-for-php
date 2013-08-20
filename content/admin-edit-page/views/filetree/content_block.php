<?php if(!defined('SYSTEM')) die('no access'); ?>
<input type="hidden" name="path" value="<?php echo $path; ?>" />
<textarea style="height:450px;width:780px;background:#141414;color:#fff;" name="block"><?php echo $content; ?></textarea>
<input type="submit" name="submit" value=" <?php echo SUBMIT; ?> " onClick="submit_content_block();return false;"/>