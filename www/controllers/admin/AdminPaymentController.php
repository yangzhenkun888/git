<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class AdminPaymentControllerCore extends AdminController
{
	public $payment_modules = array();

	public function __construct()
	{
		parent::__construct();

		$shop_id = Context::getContext()->shop->id;

		/* Get all modules then select only payment ones */
		$modules = Module::getModulesOnDisk(true);

		foreach ($modules as $module)
			if ($module->tab == 'payments_gateways')
			{
				if ($module->id)
				{
					if (!get_class($module) == 'SimpleXMLElement')
						$module->country = array();
					$countries = DB::getInstance()->executeS('
						SELECT id_country
						FROM '._DB_PREFIX_.'module_country
						WHERE id_module = '.(int)$module->id.' AND `id_shop`='.(int)$shop_id
					);
					foreach ($countries as $country)
						$module->country[] = $country['id_country'];

					if (!get_class($module) == 'SimpleXMLElement')
						$module->currency = array();
					$currencies = DB::getInstance()->executeS('
						SELECT id_currency
						FROM '._DB_PREFIX_.'module_currency
						WHERE id_module = '.(int)$module->id.' AND `id_shop`='.(int)$shop_id
					);
					foreach ($currencies as $currency)
						$module->currency[] = $currency['id_currency'];

					if (!get_class($module) == 'SimpleXMLElement')
						$module->group = array();
					$groups = DB::getInstance()->executeS('
						SELECT id_group
						FROM '._DB_PREFIX_.'module_group
						WHERE id_module = '.(int)$module->id.' AND `id_shop`='.(int)$shop_id
					);
					foreach ($groups as $group)
						$module->group[] = $group['id_group'];
				}
				else
				{
					$module->country = null;
					$module->currency = null;
					$module->group = null;
				}

				$this->payment_modules[] = $module;
			}
	}

	public function postProcess()
	{
		if ($this->action)
			$this->saveRestrictions($this->action);
	}

	public function initProcess()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			if (Tools::isSubmit('submitModulecountry'))
				$this->action = 'country';
			else if (Tools::isSubmit('submitModulecurrency'))
				$this->action = 'currency';
			else if (Tools::isSubmit('submitModulegroup'))
				$this->action = 'group';
		}
		else
			$this->errors[] = Tools::displayError('You do not have permission to edit here.');
	}


	protected function saveRestrictions($type)
	{
		// Delete type restrictions for active module.
		$modules = array();
		foreach ($this->payment_modules as $module)
			if ($module->active)
				$modules[] = (int)$module->id;

		Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'module_'.bqSQL($type).'`
			WHERE id_shop = '.Context::getContext()->shop->id.'
			AND `id_module` IN ('.implode(', ', $modules).')'
		);

		// Fill the new restriction selection for active module.
		$values = array();
		foreach ($this->payment_modules as $module)
			if ($module->active && isset($_POST[$module->name.'_'.$type.'']))
				foreach ($_POST[$module->name.'_'.$type.''] as $selected)
					$values[] = '('.(int)$module->id.', '.(int)Context::getContext()->shop->id.', '.(int)$selected.')';

		if (count($values))
			Db::getInstance()->execute('
				INSERT INTO `'._DB_PREFIX_.'module_'.bqSQL($type).'`
				(`id_module`, `id_shop`, `id_'.bqSQL($type).'`)
				VALUES '.implode(',', $values));

		Tools::redirectAdmin(self::$currentIndex.'&conf=4'.'&token='.$this->token);
	}

	public function initContent()
	{
		$this->display = 'view';
		return parent::initContent();
	}

	public function renderView()
	{
		$this->toolbar_title = $this->l('Payment');
		unset($this->toolbar_btn['back']);
		
		$shop_context = (!Shop::isFeatureActive() || Shop::getContext() == Shop::CONTEXT_SHOP);
		if (!$shop_context)
		{
			$this->tpl_view_vars = array('shop_context' => $shop_context);
			return parent::renderView();
		}
	
		// link to modules page
		if (isset($this->payment_modules[0]))
			$token_modules = Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id);

		$display_restrictions = false;
		foreach ($this->payment_modules as $module)
			if ($module->active)
			{
				$display_restrictions = true;
				break;
			}

		$lists = array(
					array('items' => Currency::getCurrencies(),
						  'title' => $this->l('Currency restrictions'),
						  'desc' => $this->l('Please mark the checkbox(es) for the currency or currencies for which you want the payment module(s) to be available.'),
						  'name_id' => 'currency',
						  'identifier' => 'id_currency',
						  'icon' => 'dollar',
					),
					array('items' => Group::getGroups($this->context->language->id),
						  'title' => $this->l('Group restrictions'),
						  'desc' => $this->l('Please mark the checkbox(es) for the groups for which you want the payment module(s) available.'),
						  'name_id' => 'group',
						  'identifier' => 'id_group',
						  'icon' => 'group',
					),
					array('items' =>Country::getCountries($this->context->language->id),
						  'title' => $this->l('Country restrictions'),
						  'desc' => $this->l('Please mark the checkbox(es) for the country or countries for which you want the payment module(s) to be available.'),
						  'name_id' => 'country',
						  'identifier' => 'id_country',
						  'icon' => 'world',
					)
				);

		foreach ($lists as $key_list => $list)
		{
			$list['check_list'] = array();
			foreach ($list['items'] as $key_item => $item)
			{
				$name_id = $list['name_id'];
				foreach ($this->payment_modules as $key_module => $module)
				{
					if (isset($module->$name_id) && in_array($item['id_'.$name_id], $module->$name_id))
						$list['items'][$key_item]['check_list'][$key_module] = 'checked';
					else
						$list['items'][$key_item]['check_list'][$key_module] = 'unchecked';

					if (!isset($module->$name_id))
						$module->$name_id = array();
					if (!isset($module->currencies_mode))
						$module->currencies_mode = '';
					if (!isset($module->currencies))
						$module->currencies = '';

					// If is a country list and the country is limited, remove it from list
					if ($name_id == 'country'
						&& isset($module->limited_countries)
						&& !empty($module->limited_countries)
						&& !(in_array(strtoupper($item['iso_code']), array_map('strtoupper', $module->limited_countries))))
						$list['items'][$key_item]['check_list'][$key_module] = null;
				}
			}
			// update list
			$lists[$key_list] = $list;
		}

		$this->tpl_view_vars = array(
			'url_modules' => isset($token_modules) ? 'index.php?tab=AdminModules&token='.$token_modules.'&&filterCategory=payments_gateways' : null,
			'display_restrictions' => $display_restrictions,
			'lists' => $lists,
			'ps_base_uri' => __PS_BASE_URI__,
			'payment_modules' => $this->payment_modules,
			'url_submit' => self::$currentIndex.'&token='.$this->token,
			'shop_context' => $shop_context
		);

		return parent::renderView();
	}
}

