{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

{capture name=path}{l s='Shipping' mod='cashondelivery'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summation' mod='cashondelivery'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<h3>{l s='Cash on delivery (COD) payment' mod='cashondelivery'}</h3>

<form action="{$link->getModuleLink('cashondelivery', 'validation', [], true)}" method="post">
	<input type="hidden" name="confirm" value="1" />
	<p>
		<img src="{$this_path}cashondelivery.jpg" alt="{l s='Cash on delivery (COD) payment' mod='cashondelivery'}" style="float:left; margin: 0px 10px 5px 0px;" />
		{l s='You have chosen the cash on delivery method.' mod='cashondelivery'}
		<br/><br />
		{l s='The total amount of your order is' mod='cashondelivery'}
		<span id="amount_{$currencies.0.id_currency}" class="price">{convertPrice price=$total}</span>
		{if $use_taxes == 1}
		    {l s='(tax incl.)' mod='cashondelivery'}
		{/if}
	</p>
	<p>
		<br /><br />
		<br /><br />
		<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='cashondelivery'}.</b>
	</p>
	<p class="cart_navigation">
		<a href="{$link->getPageLink('order', true)}?step=3" class="button_large">{l s='Other payment methods' mod='cashondelivery'}</a>
		<input type="submit" name="submit" value="{l s='I confirm my order' mod='cashondelivery'}" class="exclusive_large" />
	</p>
</form>
