{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

<!-- MODULE Home Block best sellers -->
<div id="best-sellers_block_center" class="block products_block">
	<h4>{l s='Top sellers' mod='blockbestsellers'}</h4>
	{if isset($best_sellers) AND $best_sellers}
		<div class="block_content">
			{assign var='liHeight' value=320}
			{assign var='nbItemsPerLine' value=4}
			{assign var='nbLi' value=$best_sellers|@count}
			{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
			{math equation="nbLines*liHeight" nbLines=$nbLines|ceil liHeight=$liHeight assign=ulHeight}
			<ul style="height:{$ulHeight}px;">
			{foreach from=$best_sellers item=product name=myLoop}
				<li style="border-bottom:0" class="ajax_block_product {if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.myLoop.iteration%$nbItemsPerLine == 1}clear{/if} {if $smarty.foreach.myLoop.iteration > ($smarty.foreach.myLoop.total - ($smarty.foreach.myLoop.total % $nbItemsPerLine))}last_line{/if}">
					<h5><a href="{$product.link}" title="{$product.name|truncate:32:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:27:'...'|escape:'htmlall':'UTF-8'}</a></h5>
					<div class="product_desc"><a href="{$product.link}" title="{l s='More' mod='blockbestsellers'}">{$product.description_short|strip_tags|truncate:130:'...'}</a></div>
					<a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.name|escape:html:'UTF-8'}" /></a>
					<div>
						{if !$PS_CATALOG_MODE}<p class="price_container"><span class="price">{$product.price}</span></p>{else}<div style="height:21px;"></div>{/if}			
						<a class="button" href="{$product.link}" title="{l s='View' mod='blockbestsellers'}">{l s='View' mod='blockbestsellers'}</a>
					</div>
				</li>
			{/foreach}
			</ul>
			<p class="clearfix" style="padding: 5px;"><a style="float:right;" href="{$link->getPageLink('best-sales')}" title="{l s='All best sellers' mod='blockbestsellers'}" class="button_large">{l s='All best sellers' mod='blockbestsellers'}</a></p>
		</div>
	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
	<br class="clear"/>
</div>
<!-- /MODULE Home Block best sellers -->
