<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class AdminZonesControllerCore extends AdminController
{
	public $asso_type = 'shop';

	public function __construct()
	{
	 	$this->table = 'zone';
		$this->className = 'Zone';
	 	$this->lang = false;
		
		$this->fields_list = array(
			'id_zone' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 25
			),
			'name' => array(
				'title' => $this->l('Zone'),
				'width' => 'auto'
			),
			'active' => array(
				'title' => $this->l('Enabled'),
				'width' => '70',
				'align' => 'center',
				'active' => 'status',
				'type' => 'bool',
				'orderby' => false
			)
		);
		$this->bulk_actions = array(
			'delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')),
			'enableSelection' => array('text' => $this->l('Enable selection')),
			'disableSelection' => array('text' => $this->l('Disable selection'))
			);
			
		parent::__construct();
	}

	public function renderList()
	{
		$this->addRowAction('edit');
		$this->addRowAction('delete');

		return parent::renderList();
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Zones'),
				'image' => '../img/admin/world.gif'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Name'),
					'name' => 'name',
					'size' => 33,
					'required' => true,
					'desc' => $this->l('Zone name (e.g. Africa, West Coast, Neighboring Countries)'),
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Active:'),
					'name' => 'active',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'desc' => $this->l('Allow or disallow shipping to this zone')
				)
			)
		);

		if (Shop::isFeatureActive())
		{
			$this->fields_form['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Group shop association:'),
				'name' => 'checkBoxShopAsso',
			);
		}

		$this->fields_form['submit'] = array(
			'title' => $this->l('Save   '),
			'class' => 'button'
		);

		return parent::renderForm();
	}
}


