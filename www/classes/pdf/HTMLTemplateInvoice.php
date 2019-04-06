<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

/**
 * @since 1.5
 */
class HTMLTemplateInvoiceCore extends HTMLTemplate
{
	public $order;
	public $available_in_your_account = false;

	public function __construct(OrderInvoice $order_invoice, $smarty)
	{
		$this->order_invoice = $order_invoice;
		$this->order = new Order((int)$this->order_invoice->id_order);
		$this->smarty = $smarty;

		// header informations
		$this->date = Tools::displayDate($order_invoice->date_add, (int)$this->order->id_lang);

		$id_lang = Context::getContext()->language->id;
		$this->title = HTMLTemplateInvoice::l('Invoice ').' #'.Configuration::get('PS_INVOICE_PREFIX', $id_lang).sprintf('%06d', $order_invoice->number);
		// footer informations
		$this->shop = new Shop((int)$this->order->id_shop);
	}

	/**
	 * Returns the template's HTML content
	 * @return string HTML content
	 */
	public function getContent()
	{
		$country = new Country((int)$this->order->id_address_invoice);
		$invoice_address = new Address((int)$this->order->id_address_invoice);
		$formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
		$formatted_delivery_address = '';

		if ($this->order->id_address_delivery != $this->order->id_address_invoice)
		{
			$delivery_address = new Address((int)$this->order->id_address_delivery);
			$formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');
		}

		$customer = new Customer((int)$this->order->id_customer);

		$this->smarty->assign(array(
			'order' => $this->order,
			'order_details' => $this->order_invoice->getProducts(),
			'cart_rules' => $this->order->getCartRules($this->order_invoice->id),
			'delivery_address' => $formatted_delivery_address,
			'invoice_address' => $formatted_invoice_address,
			'tax_excluded_display' => Group::getPriceDisplayMethod($customer->id_default_group),
			'tax_tab' => $this->getTaxTabContent(),
			'customer' => $customer
		));

		return $this->smarty->fetch($this->getTemplateByCountry($country->iso_code));
	}

	/**
	 * Returns the tax tab content
	 */
	public function getTaxTabContent()
	{
			$invoice_address = new Address((int)$this->order->id_address_invoice);
			$tax_exempt = Configuration::get('VATNUMBER_MANAGEMENT')
								&& !empty($invoice_address->vat_number)
								&& $invoice_address->id_country != Configuration::get('VATNUMBER_COUNTRY');

			$this->smarty->assign(array(
				'tax_exempt' => $tax_exempt,
				'use_one_after_another_method' => $this->order_invoice->useOneAfterAnotherTaxComputationMethod(),
				'product_tax_breakdown' => $this->order_invoice->getProductTaxesBreakdown(),
				'shipping_tax_breakdown' => $this->order_invoice->getShippingTaxesBreakdown($this->order),
				'ecotax_tax_breakdown' => $this->order_invoice->getEcoTaxTaxesBreakdown(),
				'wrapping_tax_breakdown' => $this->order_invoice->getWrappingTaxesBreakdown(),
				'order' => $this->order,
				'order_invoice' => $this->order_invoice
			));

			return $this->smarty->fetch($this->getTemplate('invoice.tax-tab'));
	}

	/**
	 * Returns the invoice template associated to the country iso_code
	 * @param string $iso_country
	 */
	protected function getTemplateByCountry($iso_country)
	{
		$file = Configuration::get('PS_INVOICE_MODEL');

		// try to fetch the iso template
		$template = $this->getTemplate($file.'.'.$iso_country);

		// else use the default one
		if (!$template)
			$template = $this->getTemplate($file);

		return $template;
	}

	/**
	 * Returns the template filename when using bulk rendering
	 * @return string filename
	 */
	public function getBulkFilename()
	{
		return 'invoices.pdf';
	}

	/**
	 * Returns the template filename
	 * @return string filename
	 */
	public function getFilename()
	{
		return Configuration::get('PS_INVOICE_PREFIX').sprintf('%06d', $this->order_invoice->number).'.pdf';
	}
}

