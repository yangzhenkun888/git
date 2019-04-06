<?php /*%%SmartyHeaderCode:9071679355ca4c84875a715-24247237%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd7c6ca9b1d8d11221e937f4127c0d78bc9a77bf4' => 
    array (
      0 => '/usr/local/nginx-1.14/html/www/modules/blockcategories/blockcategories_footer.tpl',
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
  'nocache_hash' => '9071679355ca4c84875a715-24247237',
  'cache_lifetime' => 31536000,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca4cc04c570b7_43110080',
  'variables' => 
  array (
    'widthColumn' => 0,
    'isDhtml' => 0,
    'blockCategTree' => 0,
    'child' => 0,
    'numberColumn' => 0,
  ),
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca4cc04c570b7_43110080')) {function content_5ca4cc04c570b7_43110080($_smarty_tpl) {?>
<!-- Block categories module -->
<div class="blockcategories_footer">
	<h4>分类目录</h4>
<div class="category_footer" style="float:left;clear:none;width:100%">
	<div style="float:left" class="list">
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
	</div>
</div>
<br class="clear"/>
</div>
<!-- /Block categories module -->
<?php }} ?>