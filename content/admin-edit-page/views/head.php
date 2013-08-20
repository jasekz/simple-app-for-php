<?php if(!defined('SYSTEM')) die('no access');  ?>
<?php do_include('admin','head'); ?>
<script language="Javascript" type="text/javascript" src="<?php echo TEMPLATE_PATH; ?>/scripts/jquery.js"></script>
<style>
.textarea {
	display:none;
	width:99.5%;
	height:500px;
	background:#4A4949;
	color:#00CC66; 
	overflow:auto;
	border:2px solid #707199;
}
.controls {
	font-size:9px;
	margin-left:5px;
	float:right;
	display:none;
}
.controls a {
	margin-left:15px;
}
.row {
	padding:0px 5px 0px 5px;
}
.row input {
	float:right;
	display:none;
}
.highlight {
	background:#D8D6F8;
}
.toggle {
	margin-right:20px;
}
input[type=submit], table tr td input[type=submit] {
    cursor: pointer;   
	margin:3px;  
}

/* xpander */
#xpander ul {
	list-style-type: none;
}
#xpander ul li {
	width:100%;
	font-size:11px;
}
#xpander a, div#xpander ul li a.toggle, #xpander li  {
	color: #3F7378;
	text-decoration: none;
	padding-bottom: 3px;
}
#xpander ul li img {
	margin:5px 5px 0px 0px;
}
#xpander ul {
	padding-left: 15px;
}
div#xpander ul li a:hover {
	color: #000;
}
</style>

<script type="text/javascript">
var tpl_path = '<?php echo TEMPLATE_PATH; ?>'; 
$(document).ready(function() {
	$('a.toggle').click(function() {
		$(this).parent().next().toggle(500);
		$('textarea').slideUp(500);
		$('.row input').hide();
		$('a.edit').css({'font-weight':'normal','color':'#3F7378'}).removeClass('open').addClass('closed');	
		if($(this).hasClass('open')){
			$(this).removeClass('open').addClass('closed').prev().attr('src',tpl_path+'/images/folder_closed.png');
		}
		else {
			$(this).removeClass('closed').addClass('open').prev().attr('src',tpl_path+'/images/folder_open.png');
		}
		return false;
	}); 
	
	$('a.edit').click(function(){
		$('a.edit').prev().hide();
		if($(this).hasClass('open')){ 
			$('a.edit').css({'font-weight':'normal','color':'#3F7378'}).removeClass('open').addClass('closed');				
			$(this).siblings('textarea').slideUp(500);    		
			return false;
		}
		else {
			$('a.edit').css({'font-weight':'normal','color':'#3F7378'}).removeClass('open').addClass('closed');
    		$('textarea').slideUp();
    		$(this).prev().show();
    		$(this).css({'font-weight':'bold','color':'#000'}).removeClass('closed').addClass('open').siblings('textarea').slideDown(500);   		
			return false;
		}		
	});
	$('a.edit, a.toggle').hover(
		function(){
			$('.row').removeClass('highlight');
			$(this).parent().addClass('highlight');
			$('.controls').hide();
			$(this).next().show();
		}
	)
});
</script>