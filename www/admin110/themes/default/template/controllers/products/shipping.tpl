{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

<input type="hidden" name="submitted_tabs[]" value="Shipping" />
<h4 class="tab">1. {l s='Info.'}</h4>
<h4>{l s='Shipping'}</h4>

{if isset($display_common_field) && $display_common_field}
	<div class="hint" style="display: block">{l s='Warning, if you change the value of fields with an orange bullet %s, the value will be changed for all other shops for this product' sprintf=$bullet_common_field}</div>
{/if}

<div class="separation"></div>

<table>
	<tr>
		<td class="col-left"><label>{l s='Width (package):'}</label></td>
		<td style="padding-bottom:5px;">
			<input size="6" maxlength="6" name="width" type="text" value="{$product->width}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />{$bullet_common_field}  {$ps_dimension_unit}
		</td>
	</tr>
	<tr>
		<td class="col-left"><label>{l s='Height (package):'}</label></td>
		<td style="padding-bottom:5px;">
			<input size="6" maxlength="6" name="height" type="text" value="{$product->height}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />{$bullet_common_field}  {$ps_dimension_unit}
		</td>
	</tr>
	<tr>
	<td class="col-left"><label>{l s='Depth (package):'}</label></td>
	<td style="padding-bottom:5px;">
	<input size="6" maxlength="6" name="depth" type="text" value="{$product->depth}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />{$bullet_common_field}  {$ps_dimension_unit}
	</td>
	</tr>
	<tr>
	<td class="col-left"><label>{l s='Weight (package):'}</label></td>
	<td style="padding-bottom:5px;">
	<input size="6" maxlength="6" name="weight" type="text" value="{$product->weight}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />{$bullet_common_field}  {$ps_weight_unit}
	</td>
	</tr>
	<tr>
		<td class="col-left"><label>{l s='Additional shipping cost (per quantity):'}</label></td>
		<td style="padding-bottom:5px;">{$currency->prefix}<input type="text" name="additional_shipping_cost"
				value="{$product->additional_shipping_cost|htmlentities}" />{$currency->suffix}
			{if $country_display_tax_label}{l s='tax excl.'}{/if}
			<p class="preference_description">{l s='Carrier tax will be applied.'}</p>
		</td>
	</tr>
	<tr>
		<td class="col-left">
			<label>{l s='Carriers:'}</label>
		</td>
		<td class="padding-bottom:5px;">
			<select name="carriers[]" id="carriers_restriction" multiple="multiple" size="4" style="height:100px;width:200px;">
				{foreach $carrier_list as $carrier}
					<option value="{$carrier.id_reference}" {if isset($carrier.selected) && $carrier.selected}selected="selected"{/if}>{$carrier.name}</option>
				{/foreach}
			</select>
			<br>
			<button class="button" onclick="unselectAllCarriers(); return false;">{l s='Unselect all'}</button>
			<p class="preference_description">{l s='If no carrier selected, all carriers could be used to ship this product.'}</p>
		</td>
	</tr>
</table>
<script>
	function unselectAllCarriers()
	{
		$('#carriers_restriction option').each(function () { $(this).removeAttr('selected')});
		return false;
	}
</script>