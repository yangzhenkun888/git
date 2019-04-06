<?php /* Smarty version Smarty-3.1.8, created on 2019-04-03 15:06:44
         compiled from "/usr/local/nginx-1.14/html/www/modules/blockreinsurance/blockreinsurance.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9239015175ca4c8487303b7-52294333%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6cb666f5b261f2557a02566cd9ce552ef8980e31' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/blockreinsurance/blockreinsurance.tpl',
      1 => 1554132583,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9239015175ca4c8487303b7-52294333',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca4c84874d1c2_01271857',
  'variables' => 
  array (
    'infos' => 0,
    'nbblocks' => 0,
    'module_dir' => 0,
    'info' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca4c84874d1c2_01271857')) {function content_5ca4c84874d1c2_01271857($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/usr/local/nginx-1.14/html/www/tools/smarty/plugins/modifier.escape.php';
?>
<?php if (count($_smarty_tpl->tpl_vars['infos']->value)>0){?>
<!-- MODULE Block reinsurance -->
<div id="reinsurance_block" class="clearfix">
	<ul class="width<?php echo $_smarty_tpl->tpl_vars['nbblocks']->value;?>
">	
		<?php  $_smarty_tpl->tpl_vars['info'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['info']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['infos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['info']->key => $_smarty_tpl->tpl_vars['info']->value){
$_smarty_tpl->tpl_vars['info']->_loop = true;
?>
			<li><img src="<?php echo $_smarty_tpl->tpl_vars['module_dir']->value;?>
img/<?php echo $_smarty_tpl->tpl_vars['info']->value['file_name'];?>
" <?php if ($_smarty_tpl->tpl_vars['info']->value['text']!=''){?>alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['info']->value['text'], 'html', 'UTF-8');?>
"<?php }?> /> <?php if ($_smarty_tpl->tpl_vars['info']->value['text']!=''){?><span><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['info']->value['text'], 'html', 'UTF-8');?>
</span><?php }?></li>
		<?php } ?>
	</ul>
</div>
<!-- /MODULE Block reinsurance -->
<?php }?><?php }} ?>