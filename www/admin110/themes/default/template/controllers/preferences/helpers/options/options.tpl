{**
 * MILEBIZ �����̳�
 * ============================================================================
 * ��Ȩ���� 2011-20__ ��������
 * ��վ��ַ: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

{extends file="helpers/options/options.tpl"}
{block name="input"}
	{if $field['type'] == 'disabled'}
		{$field['disabled']}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}