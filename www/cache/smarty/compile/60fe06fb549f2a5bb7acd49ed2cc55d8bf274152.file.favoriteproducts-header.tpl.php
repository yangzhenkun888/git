<?php /* Smarty version Smarty-3.1.8, created on 2019-04-03 15:06:44
         compiled from "/usr/local/nginx-1.14/html/www/modules/favoriteproducts/views/templates/hook/favoriteproducts-header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14097023745ca4c847985498-18189184%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '60fe06fb549f2a5bb7acd49ed2cc55d8bf274152' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/favoriteproducts/views/templates/hook/favoriteproducts-header.tpl',
      1 => 1554132584,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14097023745ca4c847985498-18189184',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca4c8479bc053_75151208',
  'variables' => 
  array (
    'link' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca4c8479bc053_75151208')) {function content_5ca4c8479bc053_75151208($_smarty_tpl) {?>
<script type="text/javascript">
	var favorite_products_url_add = '<?php echo $_smarty_tpl->tpl_vars['link']->value->getModuleLink('favoriteproducts','actions',array('process'=>'add'),true);?>
';
	var favorite_products_url_remove = '<?php echo $_smarty_tpl->tpl_vars['link']->value->getModuleLink('favoriteproducts','actions',array('process'=>'remove'),true);?>
';
<?php if (isset($_GET['id_product'])){?>
	var favorite_products_id_product = '<?php echo intval($_GET['id_product']);?>
';
<?php }?> 
</script>
<?php }} ?>