{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

<div id="mywishlist">
	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account' mod='blockwishlist'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My wishlists' mod='blockwishlist'}{/capture}
	{include file="$tpl_dir./breadcrumb.tpl"}

	<h2>{l s='My wishlists' mod='blockwishlist'}</h2>

	{include file="$tpl_dir./errors.tpl"}

	{if $id_customer|intval neq 0}
		<form method="post" class="std" id="form_wishlist">
			<fieldset>
				<h3>{l s='New wishlist' mod='blockwishlist'}</h3>
				<p class="text">
					<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
					<label class="align_right" for="name">{l s='Name' mod='blockwishlist'}</label>
					<input type="text" id="name" name="name" class="inputTxt" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'htmlall':'UTF-8'}{/if}" />
				</p>
				<p class="submit">
					<input type="submit" name="submitWishlist" id="submitWishlist" value="{l s='Save' mod='blockwishlist'}" class="exclusive" />
				</p>
			</fieldset>
		</form>
		{if $wishlists}
		<div id="block-history" class="block-center">
			<table class="std">
				<thead>
					<tr>
						<th class="first_item">{l s='Name' mod='blockwishlist'}</th>
						<th class="item mywishlist_first">{l s='Qty' mod='blockwishlist'}</th>
						<th class="item mywishlist_first">{l s='Viewed' mod='blockwishlist'}</th>
						<th class="item mywishlist_second">{l s='Created' mod='blockwishlist'}</th>
						<th class="item mywishlist_second">{l s='Direct Link' mod='blockwishlist'}</th>
						<th class="last_item mywishlist_first">{l s='Delete' mod='blockwishlist'}</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$wishlists}
					<tr id="wishlist_{$wishlists[i].id_wishlist|intval}">
						<td style="width:200px;">
							<a href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{$wishlists[i].name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a>
						</td>
						<td class="bold align_center">
							{assign var=n value=0}
							{foreach from=$nbProducts item=nb name=i}
								{if $nb.id_wishlist eq $wishlists[i].id_wishlist}
									{assign var=n value=$nb.nbProducts|intval}
								{/if}
							{/foreach}
							{if $n}
								{$n|intval}
							{else}
								0
							{/if}
						</td>
						<td>{$wishlists[i].counter|intval}</td>
						<td>{$wishlists[i].date_add|date_format:"%Y-%m-%d"}</td>
						<td><a href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{l s='View' mod='blockwishlist'}</a></td>
						<td class="wishlist_delete">
							<a href="javascript:;"onclick="return (WishlistDelete('wishlist_{$wishlists[i].id_wishlist|intval}', '{$wishlists[i].id_wishlist|intval}', '{l s='Do you really want to delete this wishlist ?' mod='blockwishlist'}'));">{l s='Delete' mod='blockwishlist'}</a>
						</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</div>
		<div id="block-order-detail">&nbsp;</div>
		{/if}
	{/if}

	<ul class="footer_links">
		<li><a href="{$link->getPageLink('my-account', true)}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account', true)}">{l s='Back to Your Account' mod='blockwishlist'}</a></li>
		<li class="f_right"><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Home' mod='blockwishlist'}</a></li>
	</ul>
</div>
