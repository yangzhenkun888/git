<?php
/**
 * MILEBIZ �����̳�
 * ============================================================================
 * ��Ȩ���� 2011-20__ ��������
 * ��վ��ַ: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

/**
 * Represents the products kept in warehouses
 *
 * @since 1.5.0
 */
class StockCore extends ObjectModel
{
	/** @var int identifier of the warehouse */
	public $id_warehouse;

	/** @var int identifier of the product */
	public $id_product;

	/** @var int identifier of the product attribute if necessary */
	public $id_product_attribute;

	/** @var string Product reference */
	public $reference;

	/** @var int Product EAN13 */
	public $ean13;

	/** @var string UPC */
	public $upc;

	/** @var int the physical quantity in stock for the current product in the current warehouse */
	public $physical_quantity;

	/** @var int the usable quantity (for sale) of the current physical quantity */
	public $usable_quantity;

	/** @var int the unit price without tax forthe current product */
	public $price_te;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'stock',
		'primary' => 'id_stock',
		'fields' => array(
			'id_warehouse' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_product' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_product_attribute' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'reference' => 				array('type' => self::TYPE_STRING, 'validate' => 'isReference'),
			'ean13' => 					array('type' => self::TYPE_STRING, 'validate' => 'isEan13'),
			'upc' => 					array('type' => self::TYPE_STRING, 'validate' => 'isUpc'),
			'physical_quantity' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'usable_quantity' => 		array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true),
			'price_te' => 				array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
		),
	);

	/**
	 * @see ObjectModel::$webserviceParameters
	 */
 	protected $webserviceParameters = array(
 		'fields' => array(
 			'id_warehouse' => array('xlink_resource' => 'warehouses'),
 			'id_product' => array('xlink_resource' => 'products'),
 			'id_product_attribute' => array('xlink_resource' => 'combinations'),
 			'real_quantity' => array('getter' => 'getWsRealQuantity', 'setter' => false),
 		),
 		'hidden_fields' => array(
 		),
 	);

	/**
	 * @see ObjectModel::update()
	 */
	public function update($null_values = false)
	{
		$this->getProductInformations();

		return parent::update($null_values);
	}

	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $null_values = false)
	{
		$this->getProductInformations();

		return parent::add($autodate, $null_values);
	}

	/**
	 * Gets reference, ean13 and upc of the current product
	 * Stores it in stock for stock_mvt integrity and history purposes
	 */
	protected function getProductInformations()
	{
		// if combinations
		if ((int)$this->id_product_attribute > 0)
		{
			$query = new DbQuery();
			$query->select('reference, ean13, upc');
			$query->from('product_attribute');
			$query->where('id_product = '.(int)$this->id_product);
			$query->where('id_product_attribute = '.(int)$this->id_product_attribute);
			$rows = Db::getInstance()->executeS($query);

			if (!is_array($rows))
				return;

			foreach ($rows as $row)
			{
				$this->reference = $row['reference'];
				$this->ean13 = $row['ean13'];
				$this->upc = $row['upc'];
			}
		}
		else // else, simple product
		{
			$product = new Product((int)$this->id_product);
			if (Validate::isLoadedObject($product))
			{
				$this->reference = $product->reference;
				$this->ean13 = $product->ean13;
				$this->upc = $product->upc;
			}
		}
	}

	/**
	 * Webservice : used to get the real quantity of a product
	 */
	public function getWsRealQuantity()
	{
		$manager = StockManagerFactory::getManager();
		$quantity = $manager->getProductRealQuantities($this->id_product, $this->id_product_attribute, $this->id_warehouse, true);
		return $quantity;
	}
}