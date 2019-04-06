<?php
/**
 * MILEBIZ �����̳�
 * ============================================================================
 * ��Ȩ���� 2011-20__ ��������
 * ��վ��ַ: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class AdminRangeWeightControllerCore extends AdminController
{
	public function __construct()
	{
	 	$this->table = 'range_weight';
	 	$this->className = 'RangeWeight';
	 	$this->lang = false;

		$this->addRowAction('edit');
		$this->addRowAction('delete');
	 	$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

		$this->fields_list = array(
			'id_range_weight' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'carrier_name' => array('title' => $this->l('Carrier'), 'align' => 'left', 'width' => 'auto', 'filter_key' => 'ca!name'),
			'delimiter1' => array('title' => $this->l('From'), 'width' => 86, 'type' => 'float', 'suffix' => Configuration::get('PS_WEIGHT_UNIT'), 'align' => 'right'),
			'delimiter2' => array('title' => $this->l('To'), 'width' => 86, 'type' => 'float', 'suffix' => Configuration::get('PS_WEIGHT_UNIT'), 'align' => 'right'));

		$this->_join = 'LEFT JOIN '._DB_PREFIX_.'carrier ca ON (ca.`id_carrier` = a.`id_carrier`)';
		$this->_select = 'ca.`name` AS carrier_name';
		$this->_where = 'AND ca.`deleted` = 0';

		parent::__construct();
	}

	public function renderForm()
	{
		$carriers = Carrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
		foreach ($carriers as $key => $carrier)
			if ($carrier['is_free'])
				unset($carriers[$key]);

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Weight ranges'),
				'image' => '../img/t/AdminRangeWeight.gif'
			),
			'input' => array(
				array(
					'type' => 'select',
					'label' => $this->l('Carrier:'),
					'name' => 'id_carrier',
					'required' => false,
					'desc' => $this->l('You can apply this range to a different carrier by selecting its name.'),
					'options' => array(
						'query' => $carriers,
						'id' => 'id_carrier',
						'name' => 'name'
					),
					'empty_message' => '<div style="margin:5px 0 10px 0">'.$this->l('There is no carrier available for this weight range.').'</div>'
				),
				array(
					'type' => 'text',
					'label' => $this->l('From:'),
					'name' => 'delimiter1',
					'size' => 5,
					'required' => true,
					'suffix' => Configuration::get('PS_WEIGHT_UNIT'),
					'desc' => $this->l('Range start (included)'),
				),
				array(
					'type' => 'text',
					'label' => $this->l('To:'),
					'name' => 'delimiter2',
					'size' => 5,
					'required' => true,
					'suffix' => Configuration::get('PS_WEIGHT_UNIT'),
					'desc' => $this->l('Range end (excluded)'),
				),
			),
			'submit' => array(
				'title' => $this->l('   Save   '),
				'class' => 'button'
			)
		);

		return parent::renderForm();
	}

	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
		if ($this->_list && is_array($this->_list))
			foreach ($this->_list as $key => $list)
				if ($list['carrier_name'] == '0')
					$this->_list[$key]['carrier_name'] = Configuration::get('PS_SHOP_NAME');
	}

	public function postProcess()
	{
		$id = (int)Tools::getValue('id_'.$this->table);
		
		if (Tools::getValue('submitAdd'.$this->table))
		{
			if (Tools::getValue('delimiter1') >= Tools::getValue('delimiter2'))
				$this->errors[] = Tools::displayError('Invalid range');
			else if (!$id && RangeWeight::rangeExist((int)Tools::getValue('id_carrier'), (float)Tools::getValue('delimiter1'), (float)Tools::getValue('delimiter2')))
				$this->errors[] = Tools::displayError('Range already exists');
			else if (RangeWeight::isOverlapping((int)Tools::getValue('id_carrier'), (float)Tools::getValue('delimiter1'), (float)Tools::getValue('delimiter2'), ($id ? (int)$id : null)))
				$this->errors[] = Tools::displayError('Ranges are overlapping');
			else if (!count($this->errors))
				parent::postProcess();
		}
		else
			parent::postProcess();
	}
}

