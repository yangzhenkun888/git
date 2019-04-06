<?php /* Smarty version Smarty-3.1.8, created on 2019-04-03 15:06:44
         compiled from "/usr/local/nginx-1.14/html/www/modules/blocksocial/blocksocial.tpl" */ ?>
<?php /*%%SmartyHeaderCode:252616945ca4c848815a27-46590480%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d4c44011a6bcfbd5a72b231024231d8dae2a574' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/blocksocial/blocksocial.tpl',
      1 => 1554132584,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '252616945ca4c848815a27-46590480',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca4c848835d58_28124885',
  'variables' => 
  array (
    'weibo_url' => 0,
    'boke_url' => 0,
    'rss_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca4c848835d58_28124885')) {function content_5ca4c848835d58_28124885($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/usr/local/nginx-1.14/html/www/tools/smarty/plugins/modifier.escape.php';
?>

<div id="social_block">
	<h4><?php echo smartyTranslate(array('s'=>'Follow us','mod'=>'blocksocial'),$_smarty_tpl);?>
</h4>
	<ul>
		<?php if ($_smarty_tpl->tpl_vars['weibo_url']->value!=''){?><li class="facebook"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['weibo_url']->value, 'html', 'UTF-8');?>
"><?php echo smartyTranslate(array('s'=>'我的微博','mod'=>'blocksocial'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['boke_url']->value!=''){?><li class="twitter"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['boke_url']->value, 'html', 'UTF-8');?>
"><?php echo smartyTranslate(array('s'=>'我的博客','mod'=>'blocksocial'),$_smarty_tpl);?>
</a></li><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['rss_url']->value!=''){?><li class="rss"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['rss_url']->value, 'html', 'UTF-8');?>
"><?php echo smartyTranslate(array('s'=>'RSS','mod'=>'blocksocial'),$_smarty_tpl);?>
</a></li><?php }?>
	</ul>
</div>
<?php }} ?>