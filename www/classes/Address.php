<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class AddressCore extends ObjectModel
{
	/** @var integer Customer id which address belongs to */
	public $id_customer = null;

	/** @var integer Manufacturer id which address belongs to */
	public $id_manufacturer = null;

	/** @var integer Supplier id which address belongs to */
	public $id_supplier = null;

	/**
	 * @since 1.5.0
	 * @var int Warehouse id which address belongs to
	 */
	public $id_warehouse = null;

	/** @var integer Country id */
	public $id_country;

	/** @var integer State id */
	public $id_state;

	/** @var string Country name */
	public $country;
	
	/** @var string State name */
	public $state;

	/** @var string Alias (eg. Home, Work...) */
	public $alias;

	/** @var string Company (optional) */
	public $company;

	/** @var string Lastname */
	public $lastname;

	/** @var string Firstname */
	public $firstname;

	/** @var string Address first line */
	public $address1;

	/** @var string Address second line (optional) */
	public $address2;

	/** @var string Postal code */
	public $postcode;

	/** @var string City */
	public $city;
	
	/** @var integer City id */
	public $id_city;

	/** @var string Any other useful information */
	public $other;

	/** @var string Phone number */
	public $phone;

	/** @var string Mobile phone number */
	public $phone_mobile;

	/** @var string VAT number */
	public $vat_number;

	/** @var string DNI number */
	public $dni;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var boolean True if address has been deleted (staying in database as deleted) */
	public $deleted = 0;

	protected static $_idZones = array();
	protected static $_idCountries = array();

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'address',
		'primary' => 'id_address',
		'fields' => array(
			'id_customer' => 		array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false),
			'id_manufacturer' => 	array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false),
			'id_supplier' => 		array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false),
			'id_warehouse' => 		array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false),
			'id_country' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_state' => 			array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId'),
			'alias' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 32),
			'company' => 			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 64),
			'lastname' => 			array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 32),
			'firstname' => 			array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 32),
			'vat_number' =>	 		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'address1' => 			array('type' => self::TYPE_STRING, 'validate' => 'isAddress', 'required' => true, 'size' => 128),
			'address2' => 			array('type' => self::TYPE_STRING, 'validate' => 'isAddress', 'size' => 128),
			'postcode' => 			array('type' => self::TYPE_STRING, 'validate' => 'isPostCode', 'size' => 12),
			'id_city' => 			array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'required' => true),
			'other' => 				array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 300),
			'phone' => 				array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 16),
			'phone_mobile' => 		array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 16),
			'dni' => 				array('type' => self::TYPE_STRING, 'validate' => 'isDniLite', 'size' => 16),
			'deleted' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
			'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'copy_post' => false),
			'date_upd' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'copy_post' => false),
		),
	);

	protected $_includeVars = array('addressType' => 'table');
	protected $_includeContainer = false;

	protected $webserviceParameters = array(
		'objectsNodeName' => 'addresses',
		'fields' => array(
			'id_customer' => array('xlink_resource'=> 'customers'),
			'id_manufacturer' => array('xlink_resource'=> 'manufacturers'),
			'id_supplier' => array('xlink_resource'=> 'suppliers'),
			'id_warehouse' => array('xlink_resource'=> 'warehouse'),
			'id_country' => array('xlink_resource'=> 'countries'),
			'id_state' => array('xlink_resource'=> 'states'),
		),
	);

	/**
	 * Build an address
	 *
	 * @param integer $id_address Existing address id in order to load object (optional)
	 */
	public	function __construct($id_address = null, $id_lang = null)
	{
		parent::__construct($id_address);

		/* Get and cache address country name */
		if ($this->id){
			$this->country = Country::getNameById($id_lang ? $id_lang : Configuration::get('PS_LANG_DEFAULT'), $this->id_country);
			$this->city = City::getNameById($this->id_city);
			$this->state = State::getNameById($this->id_state);
		}
	}

	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $null_values = false)
	{
		if (!parent::add($autodate, $null_values))
			return false;

		if (Validate::isUnsignedId($this->id_customer))
			Customer::resetAddressCache($this->id_customer);
		return true;
	}
	
	public function update($null_values = false)
	{
		// Empty related caches
		if (isset(self::$_idCountries[$this->id]))
			unset(self::$_idCountries[$this->id]);
		if (isset(self::$_idZones[$this->id]))
			unset(self::$_idZones[$this->id]);

		return parent::update($null_values);
	}

	/**
	 * @see ObjectModel::delete()
	 */
	public function delete()
	{
		if (Validate::isUnsignedId($this->id_customer))
			Customer::resetAddressCache($this->id_customer);

		if (!$this->isUsed())
			return parent::delete();
		else
		{
			$this->deleted = true;
			return $this->update();
		}
	}

	/**
	* Returns fields required for an address in an array hash
	* @return array hash values
	*/
	public static function getFieldsValidate()
	{
		$tmp_addr = new Address();
		$out = $tmp_addr->fieldsValidate;

		unset($tmp_addr);
		return $out;
	}

	/**
	 * @see ObjectModel::validateController()
	 */
	public function validateController($htmlentities = true)
	{
		$errors = parent::validateController($htmlentities);
		if (!Configuration::get('VATNUMBER_CHECKING'))
			return $errors;
		include_once(_PS_MODULE_DIR_.'vatnumber/vatnumber.php');
		if (class_exists('VatNumber', false))
			return array_merge($errors, VatNumber::WebServiceCheck($this->vat_number));
		return $errors;
	}
	/**
	 * Get zone id for a given address
	 *
	 * @param integer $id_address Address id for which we want to get zone id
	 * @return integer Zone id
	 */
	public static function getZoneById($id_address)
	{
		if (isset(self::$_idZones[$id_address]))
			return self::$_idZones[$id_address];

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT s.`id_zone` AS id_zone_state, c.`id_zone`
		FROM `'._DB_PREFIX_.'address` a
		LEFT JOIN `'._DB_PREFIX_.'country` c ON c.`id_country` = a.`id_country`
		LEFT JOIN `'._DB_PREFIX_.'state` s ON s.`id_state` = a.`id_state`
		WHERE a.`id_address` = '.(int)$id_address);

		self::$_idZones[$id_address] = (int)((int)$result['id_zone_state'] ? $result['id_zone_state'] : $result['id_zone']);
		return self::$_idZones[$id_address];
	}

	/**
	 * Check if country is active for a given address
	 *
	 * @param integer $id_address Address id for which we want to get country status
	 * @return integer Country status
	 */
	public static function isCountryActiveById($id_address)
	{
		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT c.`active`
		FROM `'._DB_PREFIX_.'address` a
		LEFT JOIN `'._DB_PREFIX_.'country` c ON c.`id_country` = a.`id_country`
		WHERE a.`id_address` = '.(int)$id_address))
			return false;
		return ($result['active']);
	}

	/**
	 * Check if address is used (at least one order placed)
	 *
	 * @return integer Order count for this address
	 */
	public function isUsed()
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT COUNT(`id_order`) AS used
		FROM `'._DB_PREFIX_.'orders`
		WHERE `id_address_delivery` = '.(int)$this->id.'
		OR `id_address_invoice` = '.(int)$this->id);

		return isset($result['used']) ? $result['used'] : false;
	}

	public static function getCountryAndState($id_address)
	{
		if (isset(self::$_idCountries[$id_address]))
			return self::$_idCountries[$id_address];
		if ($id_address)
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT `id_country`, `id_state`, `vat_number`, `postcode` FROM `'._DB_PREFIX_.'address`
			WHERE `id_address` = '.(int)$id_address);
		else
			$result = false;
		self::$_idCountries[$id_address] = $result;
		return $result;
	}

	/**
	* Specify if an address is already in base
	*
	* @param $id_address Address id
	* @return boolean
	*/
	public static function addressExists($id_address)
	{
		$key = 'address_exists_'.(int)$id_address;
		if (!Cache::isStored($key))
				Cache::store(
					$key, Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
							SELECT `id_address`
							FROM '._DB_PREFIX_.'address a
							WHERE a.`id_address` = '.(int)$id_address));
		return Cache::retrieve($key);
	}

	public static function getFirstCustomerAddressId($id_customer, $active = true)
	{
		if (!$id_customer)
			return false;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `id_address`
			FROM `'._DB_PREFIX_.'address`
			WHERE `id_customer` = '.(int)$id_customer.' AND `deleted` = 0'.($active ? ' AND `active` = 1' : '')
		);
	}

	/**
	* Initiliaze an address corresponding to the specified id address or if empty to the
	* default shop configuration
	*
	* @param int $id_address
	* @return Address address
	*/
	public static function initialize($id_address = null)
	{
		// set the default address
		$address = new Address();
		$address->id_country = (int)Context::getContext()->country->id;
		$address->id_state = 0;
		$address->postcode = 0;

		// if an id_address has been specified retrieve the address
		if ($id_address)
		{
			$address = new Address((int)$id_address);

			if (!Validate::isLoadedObject($address))
				throw new MileBizException('Invalid address');
		}

		return $address;
	}

	/**
	 * Returns id_address for a given id_supplier
	 * @since 1.5.0
	 * @param int $id_supplier
	 * @return int $id_address
	 */
	public static function getAddressIdBySupplierId($id_supplier)
	{
		$query = new DbQuery();
		$query->select('id_address');
		$query->from('address');
		$query->where('id_supplier = '.(int)$id_supplier);
		$query->where('deleted = 0');
		$query->where('id_customer = 0');
		$query->where('id_manufacturer = 0');
		$query->where('id_warehouse = 0');
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
	}
}

