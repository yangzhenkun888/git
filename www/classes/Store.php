<?php
/**
 * MILEBIZ �����̳�
 * ============================================================================
 * ��Ȩ���� 2011-20__ ��������
 * ��վ��ַ: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class StoreCore extends ObjectModel
{
	/** @var integer Country id */
	public $id_country;

	/** @var integer State id */
	public $id_state;

	/** @var string Store name */
	public $name;

	/** @var string Address first line */
	public $address1;

	/** @var string Address second line (optional) */
	public $address2;

	/** @var string Postal code */
	public $postcode;

	/** @var integer City id*/
	public $id_city;

	/** @var float Latitude */
	public $latitude;

	/** @var float Longitude */
	public $longitude;

	/** @var string Store hours (PHP serialized) */
	public $hours;

	/** @var string Phone number */
	public $phone;

	/** @var string Fax number */
	public $fax;

	/** @var string Note */
	public $note;

	/** @var string e-mail */
	public $email;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var boolean Store status */
	public $active = true;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'store',
		'primary' => 'id_store',
		'fields' => array(
			'id_country' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_state' => 		array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId'),
			'name' => 			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
			'address1' => 		array('type' => self::TYPE_STRING, 'validate' => 'isAddress', 'required' => true, 'size' => 128),
			'address2' => 		array('type' => self::TYPE_STRING, 'validate' => 'isAddress', 'size' => 128),
			'postcode' => 		array('type' => self::TYPE_STRING, 'size' => 12),
			'id_city' => 		array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'required' => true),
			'latitude' => 		array('type' => self::TYPE_FLOAT, 'validate' => 'isCoordinate', 'size' => 12),
			'longitude' =>		array('type' => self::TYPE_FLOAT, 'validate' => 'isCoordinate', 'size' => 12),
			'hours' => 			array('type' => self::TYPE_STRING, 'validate' => 'isSerializedArray', 'size' => 254),
			'phone' => 			array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 16),
			'fax' => 			array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 16),
			'note' => 			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 65000),
			'email' => 			array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'size' => 128),
			'active' => 		array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'date_add' => 		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' => 		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		),
	);

	protected	$webserviceParameters = array(
		'fields' => array(
			'id_country' => array('xlink_resource'=> 'countries'),
			'id_state' => array('xlink_resource'=> 'states'),
			'hours' => array('getter' => 'getWsHours', 'setter' => 'setWsHours'),
		),
	);

	public function __construct($id_store = null, $id_lang = null)
	{
		parent::__construct($id_store, $id_lang);
		$this->id_image = ($this->id && file_exists(_PS_STORE_IMG_DIR_.(int)$this->id.'.jpg')) ? (int)$this->id : false;
		$this->image_dir = _PS_STORE_IMG_DIR_;
	}

	public function getWsHours()
	{
		return implode(';', Tools::unSerialize($this->hours));
	}

	public function setWsHours($hours)
	{
		$this->hours = serialize(explode(';', $hours));
		return true;
	}
}