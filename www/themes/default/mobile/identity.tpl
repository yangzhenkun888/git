{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

{capture assign='page_title'}{l s='Your personal information'}{/capture}
{include file='./page-title.tpl'}

<div data-role="content" id="content">
<a data-role="button" data-icon="arrow-l" data-theme="a" data-mini="true" data-inline="true" href="{$link->getPageLink('my-account', true)}" data-ajax="false">{l s='My account'}</a>

{include file="./errors.tpl"}

{if isset($confirmation) && $confirmation}
	<p class="success">
		{l s='Your personal information has been successfully updated.'}
		{if isset($pwd_changed)}<br />{l s='Your password has been sent to your e-mail:'} {$email|escape:'htmlall':'UTF-8'}{/if}
	</p>
{else}
	<h3>{l s='Please be sure to update your personal information if it has changed.'}</h3>
	<p class="required bold"><sup>*</sup>{l s='Required field'}</p>
	<form action="{$link->getPageLink('identity', true)}" method="post" class="std">
		<label>{l s='Title'}</label>
		<fieldset data-role="controlgroup">
		{foreach from=$genders key=k item=gender}
			<input type="radio" name="id_gender" id="id_gender{$gender->id}" value="{$gender->id}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id}checked="checked"{/if} />
			<label for="id_gender{$gender->id}" class="top">{$gender->name}</label>
		{/foreach}
		</fieldset>
		<fieldset class="required text">
			<label for="lastname">{l s='Last name'} <sup>*</sup></label>
			<input type="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" />
		</fieldset>
		<fieldset class="required text">
			<label for="firstname">{l s='First name'} <sup>*</sup></label>
			<input type="text" id="firstname" name="firstname" value="{$smarty.post.firstname}" />
		</fieldset>
		<fieldset class="required text">
			<label for="email">{l s='E-mail'} <sup>*</sup></label>
			<input type="text" name="email" id="email" value="{$smarty.post.email}" />
		</fieldset>
		<fieldset class="required text">
			<label for="old_passwd">{l s='Current Password'} <sup>*</sup></label>
			<input type="password" name="old_passwd" id="old_passwd" />
		</fieldset>
		<fieldset class="password">
			<label for="passwd">{l s='New Password'}</label>
			<input type="password" name="passwd" id="passwd" />
		</fieldset>
		<fieldset class="password">
			<label for="confirmation">{l s='Confirmation'}</label>
			<input type="password" name="confirmation" id="confirmation" />
		</fieldset>
		<label>{l s='Date of Birth'}</label>
		<fieldset data-type="horizontal" data-role="controlgroup">
			<select id="years" name="years">
				<option value="">-</option>
				{foreach from=$years item=v}
					<option value="{$v|escape:'htmlall':'UTF-8'}" {if ($sl_year == $v)}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
				{/foreach}
			</select>
			<select id="months" name="months">
				<option value="">-</option>
				{foreach from=$months key=k item=v}
					<option value="{$k|escape:'htmlall':'UTF-8'}" {if ($sl_month == $k)}selected="selected"{/if}>{l s=$v}&nbsp;</option>
				{/foreach}
			</select>
			<select name="days" id="days">
				<option value="">-</option>
				{foreach from=$days item=v}
					<option value="{$v|escape:'htmlall':'UTF-8'}" {if ($sl_day == $v)}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
				{/foreach}
			</select>
		</fieldset>
		{if $newsletter}
		<fieldset data-role="controlgroup">
			<input type="checkbox" id="newsletter" name="newsletter" value="1" {if isset($smarty.post.newsletter) && $smarty.post.newsletter == 1} checked="checked"{/if} />
			<label for="newsletter">{l s='Sign up for our newsletter'}</label>
			<input type="checkbox" name="optin" id="optin" value="1" {if isset($smarty.post.optin) && $smarty.post.optin == 1} checked="checked"{/if} />
			<label for="optin">{l s='Receive special offers from our partners'}</label>
		</fieldset>
		{/if}
		<input type="submit" data-theme="a" class="button" name="submitIdentity" value="{l s='Save'}" />
		<p id="security_informations">
			{l s='[Insert customer data privacy clause or law here, if applicable]'}
		</p>
	</form>
{/if}
</div><!-- /content -->
