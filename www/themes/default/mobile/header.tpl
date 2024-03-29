{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}
<!DOCTYPE html>
<html>
	<head>
		<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
		{*<meta name="viewport" content="width=device-width, initial-scale=1">*}
		<meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
{/if}
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta name="generator" content="MileBiz" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		<script type="text/javascript">
			var baseDir = '{$content_dir}';
			var static_token = '{$static_token}';
			var token = '{$token}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
			var priceDisplayMethod = {$priceDisplay};
			var roundMode = {$roundMode};
		</script>
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}
{if isset($js_files)}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri}"></script>
	{/foreach}
{/if}
	{$HOOK_MOBILE_HEADER}
	</head>
	<body {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}>
	<div data-role="page" {if isset($wrapper_id)}id="{$wrapper_id}"{/if} class="type-interior milebiz-page">
		<div data-role="header" id="header" class="ui-body-c">
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}" data-ajax="false"><img src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" {if $logo_image_width}width="{$logo_image_width}"{/if} {if $logo_image_height}height="{$logo_image_height}" {/if} /></a>
				</div>
				<div class="ui-block-b">
					<div id="block_cart" class="clearfix">
						{if !$PS_CATALOG_MODE}
						<a href="{$link->getPageLink('order-opc', true)}" class="link_cart" data-ajax="false">{l s='Cart'}</a>
						{/if}
						{if $logged}
							<a href="{$link->getPageLink('my-account', true)}" class="link_account" data-ajax="false">{l s='My account'}</a>
						{else}
							<a href="{$link->getPageLink('authentication', true)}" class="link_account" data-ajax="false">{l s='Log in'}</a>
						{/if}
					</div>
					{hook h="displayMobileTop"}
				</div>
			</div><!-- /grid-a -->
		</div><!-- /header -->
