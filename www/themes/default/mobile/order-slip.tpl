{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

{capture assign='page_title'}{l s='Credit slips'}{/capture}
{include file='./page-title.tpl'}

<div data-role="content" id="content">
	<a data-role="button" data-icon="arrow-l" data-theme="a" data-mini="true" data-inline="true" href="{$link->getPageLink('my-account', true)}" data-ajax="false">{l s='My account'}</a>

	<p>{l s='Credit slips you have received after cancelled orders'}.</p>
	<div class="block-center" id="block-history">
		{if $ordersSlip && count($ordersSlip)}
			<ul data-role="listview" data-theme="c" data-inset="true" data-split-theme="c" data-split-icon="milebiz-pdf">
			{foreach from=$ordersSlip item=slip name=myLoop}
				<li>
					{assign var="id_order" value={$slip.id_order|intval}}
					<a class="color-myaccount" id="order-{$id_order}" href="{$link->getPageLink('order-detail', true, null, "id_order=$id_order")}" data-ajax="false">
						<h3>{l s='Credit slip'} {l s='#'}{$slip.id_order_slip|string_format:"%06d"}</h3>
						<p>{l s='Order'} {l s='#'}{$slip.id_order|string_format:"%06d"}</p>
						<span class="ui-li-aside">{dateFormat date=$slip.date_add full=0}</span>
					</a>
					<a rel="external" data-iconshadow="false" href="{$link->getPageLink('pdf-order-slip', true, NULL, "id_order_slip={$slip.id_order_slip|intval}")}" title="{l s='Credit slip'} {l s='#'}{$slip.id_order_slip|string_format:"%06d"}" data-ajax="false">
						{l s='PDF'}
					</a>
				</li>
			{/foreach}
			</ul>
		<div id="block-order-detail" class="hidden">&nbsp;</div>
		{else}
			<p class="warning">{l s='You have not received any credit slips.'}</p>
		{/if}
	</div>
</div><!-- /content -->
