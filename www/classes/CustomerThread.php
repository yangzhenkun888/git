<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class CustomerThreadCore extends ObjectModel
{
	public $id;
	public $id_shop;
	public $id_lang;
	public $id_contact;
	public $id_customer;
	public $id_order;
	public $id_product;
	public $status;
	public $email;
	public $token;
	public $date_add;
	public $date_upd;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'customer_thread',
		'primary' => 'id_customer_thread',
		'fields' => array(
			'id_lang' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_contact' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_shop' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_customer' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_order' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'email' => 		array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'size' => 254),
			'token' => 		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			'status' => 	array('type' => self::TYPE_STRING),
			'date_add' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		),
	);

	public function delete()
	{
		if (!Validate::isUnsignedId($this->id))
			return false;
		Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'customer_message`
			WHERE `id_customer_thread` = '.(int)$this->id
		);
		return (parent::delete());
	}

	public static function getCustomerMessages($id_customer, $read = null)
	{
		$sql = 'SELECT *
			FROM '._DB_PREFIX_.'customer_thread ct
			LEFT JOIN '._DB_PREFIX_.'customer_message cm
				ON ct.id_customer_thread = cm.id_customer_thread
			WHERE id_customer = '.(int)$id_customer;
		if (!is_null($read))
			$sql .= ' AND cm.`read` = '.(int)$read;

		return Db::getInstance()->executeS($sql);
	}

	public static function getIdCustomerThreadByEmailAndIdOrder($email, $id_order)
	{
		return Db::getInstance()->getValue('
			SELECT cm.id_customer_thread
			FROM '._DB_PREFIX_.'customer_thread cm
			WHERE cm.email = \''.pSQL($email).'\'
				AND cm.id_shop = '.(int)Context::getContext()->shop->id.'
				AND cm.id_order = '.(int)$id_order
		);
	}

	public static function getContacts()
	{
		return Db::getInstance()->executeS('
			SELECT cl.*, COUNT(*) as total, (
				SELECT id_customer_thread
				FROM '._DB_PREFIX_.'customer_thread ct2
				WHERE status = "open" AND ct.id_contact = ct2.id_contact
				ORDER BY date_upd ASC
				LIMIT 1
			) as id_customer_thread
			FROM '._DB_PREFIX_.'customer_thread ct
			LEFT JOIN '._DB_PREFIX_.'contact_lang cl
				ON (cl.id_contact = ct.id_contact AND cl.id_lang = '.(int)Context::getContext()->language->id.')
			WHERE ct.status = "open"
				AND ct.id_contact IS NOT NULL
				AND cl.id_contact IS NOT NULL
			GROUP BY ct.id_contact HAVING COUNT(*) > 0
		');
	}

	public static function getTotalCustomerThreads($where = null)
	{
		if (is_null($where))
			return (int)Db::getInstance()->getValue('
				SELECT COUNT(*)
				FROM '._DB_PREFIX_.'customer_thread
			');
		else
			return (int)Db::getInstance()->getValue('
				SELECT COUNT(*)
				FROM '._DB_PREFIX_.'customer_thread
				WHERE '.$where
			);
	}

	public static function getMessageCustomerThreads($id_customer_thread)
	{
		return Db::getInstance()->executeS('
			SELECT ct.*, cm.*, cl.name subject, CONCAT(e.firstname, \' \', e.lastname) employee_name,
				CONCAT(c.firstname, \' \', c.lastname) customer_name, c.firstname
			FROM '._DB_PREFIX_.'customer_thread ct
			LEFT JOIN '._DB_PREFIX_.'customer_message cm
				ON (ct.id_customer_thread = cm.id_customer_thread)
			LEFT JOIN '._DB_PREFIX_.'contact_lang cl
				ON (cl.id_contact = ct.id_contact AND cl.id_lang = '.(int)Context::getContext()->language->id.')
			LEFT JOIN '._DB_PREFIX_.'employee e
				ON e.id_employee = cm.id_employee
			LEFT JOIN '._DB_PREFIX_.'customer c
				ON (IFNULL(ct.id_customer, ct.email) = IFNULL(c.id_customer, c.email))
			WHERE ct.id_customer_thread = '.(int)$id_customer_thread.'
			ORDER BY cm.date_add DESC
		');
	}

	public static function getNextThread($id_customer_thread)
	{
		$context = Context::getContext();
		return Db::getInstance()->getValue('
			SELECT id_customer_thread
			FROM '._DB_PREFIX_.'customer_thread ct
			WHERE ct.status = "open"
			AND ct.date_upd = (
				SELECT date_add FROM '._DB_PREFIX_.'customer_message
				WHERE (id_employee IS NULL OR id_employee = 0)
					AND id_customer_thread = '.(int)$id_customer_thread.'
				ORDER BY date_add DESC LIMIT 1
			)
			'.($context->cookie->{'customer_threadFilter_cl!id_contact'} ?
				'AND ct.id_contact = '.(int)$context->cookie->{'customer_threadFilter_cl!id_contact'} : '').'
			'.($context->cookie->{'customer_threadFilter_l!id_lang'} ?
				'AND ct.id_lang = '.(int)$context->cookie->{'customer_threadFilter_l!id_lang'} : '').
			' ORDER BY ct.date_upd ASC
		');
	}
}

