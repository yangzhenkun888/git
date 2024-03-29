<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class AdminGeolocationControllerCore extends AdminController
{
	public function __construct()
	{
		parent::__construct();

		$this->fields_options = array(
			'geolocationConfiguration' => array(
				'title' =>	$this->l('Geolocation by IP address'),
				'icon' =>	'world',
				'fields' =>	array(
		 			'PS_GEOLOCATION_ENABLED' => array(
		 				'title' => $this->l('Geolocation by IP address'),
		 				'desc' => $this->l('This option allows you, among other things, to restrict access to your shop for certain countries. See below.'),
		 				'validation' => 'isUnsignedId',
		 				'cast' => 'intval',
		 				'type' => 'bool'
					),
				),
			),
			'geolocationCountries' => array(
				'title' =>	$this->l('Options'),
				'icon' =>	'world',
				'description' => $this->l('The following features are only available if you enable the Geolocation by IP address feature.'),
				'fields' =>	array(
		 			'PS_GEOLOCATION_BEHAVIOR' => array(
						'title' => $this->l('Geolocation behavior for restricted countries'),
						'type' => 'select',
						'identifier' => 'key',
						'list' => array(array('key' => _PS_GEOLOCATION_NO_CATALOG_, 'name' => $this->l('Visitors cannot see your catalog')),
										array('key' => _PS_GEOLOCATION_NO_ORDER_, 'name' => $this->l('Visitors can see your catalog but cannot place an order'))),
					),
		 			'PS_GEOLOCATION_NA_BEHAVIOR' => array(
						'title' => $this->l('Geolocation behavior for other countries'),
						'type' => 'select',
						'identifier' => 'key',
						'list' => array(array('key' => '-1', 'name' => $this->l('All features are available')),
										array('key' => _PS_GEOLOCATION_NO_CATALOG_, 'name' => $this->l('Visitors cannot see your catalog')),
										array('key' => _PS_GEOLOCATION_NO_ORDER_, 'name' => $this->l('Visitors can see your catalog but cannot place an order')))
					),
				),
			),
			'geolocationWhitelist' => array(
				'title' =>	$this->l('IP address whitelist'),
				'icon' =>	'world',
				'description' => $this->l('You can add IP addresses that will always be allowed to access your shop (e.g. Google bots\' IP).'),
				'fields' =>	array(
		 			'PS_GEOLOCATION_WHITELIST' => array('title' => $this->l('Whitelisted IP addresses'), 'type' => 'textarea_newlines', 'cols' => 80, 'rows' => 30),
				),
				'submit' => array(),
			),
		);
	}

	/**
	 * @see AdminController::processUpdateOptions()
	 */
	public function processUpdateOptions()
	{
		if ($this->isGeoLiteCityAvailable())
			Configuration::updateValue('PS_GEOLOCATION_ENABLED', (int)Tools::getValue('PS_GEOLOCATION_ENABLED'));
		// stop processing if geolocation is set to yes but geolite pack is not available
		elseif (Tools::getValue('PS_GEOLOCATION_ENABLED'))
			$this->errors[] = Tools::displayError('Geolocation database is unavailable.');

		if (empty($this->errors))
		{
			if (!is_array(Tools::getValue('countries')) || !count(Tools::getValue('countries')))
				$this->errors[] = Tools::displayError('Country selection is invalid');
			else
			{
				Configuration::updateValue(
					'PS_GEOLOCATION_BEHAVIOR',
					(!(int)Tools::getValue('PS_GEOLOCATION_BEHAVIOR') ? _PS_GEOLOCATION_NO_CATALOG_ : _PS_GEOLOCATION_NO_ORDER_)
				);
				Configuration::updateValue('PS_GEOLOCATION_NA_BEHAVIOR', (int)Tools::getValue('PS_GEOLOCATION_NA_BEHAVIOR'));
				Configuration::updateValue('PS_ALLOWED_COUNTRIES', implode(';', Tools::getValue('countries')));
			}

			if (!Validate::isCleanHtml(Tools::getValue('PS_GEOLOCATION_WHITELIST')))
				$this->errors[] = Tools::displayError('Invalid whitelist');
			else
			{
				Configuration::updateValue(
					'PS_GEOLOCATION_WHITELIST',
					str_replace("\n", ';', str_replace("\r", '', Tools::getValue('PS_GEOLOCATION_WHITELIST')))
				);
			}
		}

		return parent::processUpdateOptions();
	}

	public function renderOptions()
	{
		// This field is not declared in class constructor because we want it to be manually post processed
		$this->fields_options['geolocationCountries']['fields']['countries'] = array(
								'title' => $this->l('Select countries that can access your store:'),
								'type' => 'checkbox_table',
								'identifier' => 'iso_code',
								'list' => Country::getCountries($this->context->language->id),
								'auto_value' => false
							);

		$this->tpl_option_vars = array('allowed_countries' => explode(';', Configuration::get('PS_ALLOWED_COUNTRIES')));

		return parent::renderOptions();
	}

	public function initContent()
	{
		$this->display = 'options';
		if (!$this->isGeoLiteCityAvailable())
			$this->displayWarning($this->l('In order to use Geolocation, please download').' 
				<a href="http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz">'.$this->l('this file').'</a> '.
				$this->l('and extract it (using Winrar or Gzip) into the /tools/geoip/ directory'));

		parent::initContent();
	}

	protected function isGeoLiteCityAvailable()
	{
		if (file_exists(_PS_GEOIP_DIR_.'GeoLiteCity.dat'))
			return true;
		return false;
	}
}

