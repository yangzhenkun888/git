<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class ShopUrlCore extends ObjectModel
{
	public $id_shop;
	public $domain;
	public $domain_ssl;
	public $physical_uri;
	public $virtual_uri;
	public $main;
	public $active;

	protected static $main_domain = null;
	protected static $main_domain_ssl = null;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'shop_url',
		'primary' => 'id_shop_url',
		'fields' => array(
			'active' => 		array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'main' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'domain' => 		array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255, 'validate' => 'isCleanHtml'),
			'domain_ssl' => 	array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isCleanHtml'),
			'id_shop' => 		array('type' => self::TYPE_INT, 'required' => true),
			'physical_uri' => 	array('type' => self::TYPE_STRING, 'size' => 64),
			'virtual_uri' => 	array('type' => self::TYPE_STRING, 'size' => 64),
		),
	);

	/**
	 * @see ObjectModel::getFields()
	 * @return array
	 */
	public function getFields()
	{
		$this->physical_uri = trim($this->physical_uri, '/');
		if ($this->physical_uri)
			$this->physical_uri = preg_replace('#/+#', '/', '/'.$this->physical_uri.'/');
		else
			$this->physical_uri = '/';

		$this->virtual_uri = trim($this->virtual_uri, '/');
		if ($this->virtual_uri)
			$this->virtual_uri = preg_replace('#/+#', '/', trim($this->virtual_uri, '/')).'/';

		return parent::getFields();
	}

	public function getBaseURI()
	{
		return $this->physical_uri.$this->virtual_uri;
	}

	public function getURL($ssl = false)
	{
		if (!$this->id)
			return;

		$url = ($ssl) ? 'https://'.$this->domain_ssl : 'http://'.$this->domain;
		return $url.$this->getBaseUri();
	}

	/**
	 * Get list of shop urls
	 *
	 * @param bool $id_shop
	 * @return Collection
	 */
	public static function getShopUrls($id_shop = false)
	{
		$urls = new Collection('ShopUrl');
		if ($id_shop)
			$urls->where('id_shop', '=', $id_shop);
		return $urls;
	}

	public function setMain()
	{
		$res = Db::getInstance()->update('shop_url', array('main' => 0), 'id_shop = '.(int)$this->id_shop);
		$res &= Db::getInstance()->update('shop_url', array('main' => 1), 'id_shop_url = '.(int)$this->id);
		$this->main = true;

		// Reset main URL for all shops to prevent problems
		$sql = 'SELECT s1.id_shop_url FROM '._DB_PREFIX_.'shop_url s1
				WHERE (
					SELECT COUNT(*) FROM '._DB_PREFIX_.'shop_url s2
					WHERE s2.main = 1
					AND s2.id_shop = s1.id_shop
				) = 0
				GROUP BY s1.id_shop';
		foreach (Db::getInstance()->executeS($sql) as $row)
			Db::getInstance()->update('shop_url', array('main' => 1), 'id_shop_url = '.$row['id_shop_url']);

		return $res;
	}

	public function canAddThisUrl($domain, $domain_ssl, $physical_uri, $virtual_uri)
	{
		$physical_uri = trim($physical_uri, '/');
		if ($physical_uri)
			$physical_uri = preg_replace('#/+#', '/', '/'.$physical_uri.'/');
		else
			$this->physical_uri = '/';

		$virtual_uri = trim($virtual_uri, '/');
		if ($virtual_uri)
			$virtual_uri = preg_replace('#/+#', '/', trim($virtual_uri, '/')).'/';

		$sql = 'SELECT id_shop_url
				FROM '._DB_PREFIX_.'shop_url
				WHERE physical_uri = \''.pSQL($physical_uri).'\'
					AND virtual_uri = \''.pSQL($virtual_uri).'\'
					AND (domain = \''.pSQL($domain).'\' '.(($domain_ssl) ? ' OR domain_ssl = \''.pSQL($domain_ssl).'\'' : '').')'
					.($this->id ? ' AND id_shop_url != '.(int)$this->id : '');
		return Db::getInstance()->getValue($sql);
	}

	public static function getMainShopDomain()
	{
		if (!self::$main_domain)
			self::$main_domain = Db::getInstance()->getValue('SELECT domain
															FROM '._DB_PREFIX_.'shop_url
															WHERE main=1 AND id_shop = '.Context::getContext()->shop->id);
		return self::$main_domain;
	}

	public static function getMainShopDomainSSL()
	{
		if (!self::$main_domain_ssl)
		{
			$sql = 'SELECT domain_ssl
					FROM '._DB_PREFIX_.'shop_url
					WHERE main = 1
						AND id_shop='.Context::getContext()->shop->id;
			self::$main_domain_ssl = Db::getInstance()->getValue($sql);
		}
		return self::$main_domain_ssl;
	}
}
