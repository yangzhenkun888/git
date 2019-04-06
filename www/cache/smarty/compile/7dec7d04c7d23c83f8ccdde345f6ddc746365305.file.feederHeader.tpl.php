<?php /* Smarty version Smarty-3.1.8, created on 2019-04-03 15:06:44
         compiled from "/usr/local/nginx-1.14/html/www/modules/feeder/feederHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14003098855ca4c8479d10f3-19761416%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7dec7d04c7d23c83f8ccdde345f6ddc746365305' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/feeder/feederHeader.tpl',
      1 => 1554132585,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14003098855ca4c8479d10f3-19761416',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca4c8479e9210_46245488',
  'variables' => 
  array (
    'meta_title' => 0,
    'feedUrl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca4c8479e9210_46245488')) {function content_5ca4c8479e9210_46245488($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/usr/local/nginx-1.14/html/www/tools/smarty/plugins/modifier.escape.php';
?>

<link rel="alternate" type="application/rss+xml" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['meta_title']->value, 'html', 'UTF-8');?>
" href="<?php echo $_smarty_tpl->tpl_vars['feedUrl']->value;?>
" /><?php }} ?>