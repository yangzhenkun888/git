<?php /*%%SmartyHeaderCode:3100150845ca4c8480be3f4-81869377%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '948e86f5e6141a77c70726174d60bb22960e6193' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/blockcategories/blockcategories.tpl',
      1 => 1554132584,
      2 => 'file',
    ),
    'f3e3cc9f2815c7c293ed83f1d77184a7a40da28e' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/blockcategories/category-tree-branch.tpl',
      1 => 1554132584,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3100150845ca4c8480be3f4-81869377',
  'cache_lifetime' => 31536000,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca4cc046cc221_43688342',
  'variables' => 
  array (
    'isDhtml' => 0,
    'blockCategTree' => 0,
    'child' => 0,
  ),
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca4cc046cc221_43688342')) {function content_5ca4cc046cc221_43688342($_smarty_tpl) {?>
<!-- Block categories module -->
<div id="categories_block_left" class="block">
	<h4>分类</h4>
	<div class="block_content">
		<ul class="tree dhtml">
									
<li >
	<a href="http://www.yzk.com/index.php?id_category=3&amp;controller=category"  title="音乐收藏、至爱的视频、app、游戏、podcast 以及更多内容。iTunes 就是你一切娱乐活动的大本营。">便携设备</a>
	</li>

												
<li >
	<a href="http://www.yzk.com/index.php?id_category=4&amp;controller=category"  title="iPod的个性化配件">配件</a>
	</li>

												
<li class="last">
	<a href="http://www.yzk.com/index.php?id_category=5&amp;controller=category"  title="最新的英特尔处理器，更大的硬盘，足够的内存。笔记本电脑的超强性能和超久待机能力，可以和任何电脑连接。帮你实现无办公桌办公。">笔记本</a>
	</li>

							</ul>
		
		<script type="text/javascript">
		// <![CDATA[
			// we hide the tree only if JavaScript is activated
			$('div#categories_block_left ul.dhtml').hide();
		// ]]>
		</script>
	</div>
</div>
<!-- /Block categories module -->
<?php }} ?>