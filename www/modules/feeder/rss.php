<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */
include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

// Get data
$number = ((int)(Tools::getValue('n')) ? (int)(Tools::getValue('n')) : 10);
$orderBy = Tools::getProductsOrder('by', Tools::getValue('orderby'));
$orderWay = Tools::getProductsOrder('way', Tools::getValue('orderway'));
$id_category = ((int)(Tools::getValue('id_category')) ? (int)(Tools::getValue('id_category')) : Configuration::get('PS_HOME_CATEGORY'));
$products = Product::getProducts((int)Context::getContext()->language->id, 0, ($number > 10 ? 10 : $number), $orderBy, $orderWay, $id_category, true);
$currency = new Currency((int)Context::getContext()->currency->id);
$affiliate = (Tools::getValue('ac') ? '?ac='.(int)(Tools::getValue('ac')) : '');

// Send feed
header("Content-Type:text/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<rss version="2.0">
	<channel>
		<title><![CDATA[<?php echo Configuration::get('PS_SHOP_NAME') ?>]]></title>
		<link><?php echo _PS_BASE_URL_.__PS_BASE_URI__; ?></link>
		<mail><?php echo Configuration::get('PS_SHOP_EMAIL') ?></mail>
		<generator>MileBiz</generator>
		<language><?php echo Context::getContext()->language->iso_code; ?></language>
		<image>
			<title><![CDATA[<?php echo Configuration::get('PS_SHOP_NAME') ?>]]></title>
			<url><?php echo _PS_BASE_URL_.__PS_BASE_URI__.'img/logo.jpg'; ?></url>
			<link><?php echo _PS_BASE_URL_.__PS_BASE_URI__; ?></link>
		</image>
<?php
	foreach ($products AS $product)
	{
		$image = Image::getImages((int)($cookie->id_lang), $product['id_product']);
		echo "\t\t<item>\n";
		echo "\t\t\t<title><![CDATA[".$product['name']." - ".html_entity_decode(Tools::displayPrice(Product::getPriceStatic($product['id_product']), $currency), ENT_COMPAT, 'UTF-8')." ]]></title>\n";
		echo "\t\t\t<description>";
		$cdata = true;
		if (is_array($image) AND sizeof($image))
		{
			$imageObj = new Image($image[0]['id_image']);
			echo "<![CDATA[<img src='"._PS_BASE_URL_._THEME_PROD_DIR_.$imageObj->getExistingImgPath()."-small_default.jpg' title='".str_replace('&', '', $product['name'])."' alt='thumb' />";
			$cdata = false;
		}
		if ($cdata)
			echo "<![CDATA[";
		echo $product['description_short']."]]></description>\n";

		echo "\t\t\t<link><![CDATA[".htmlspecialchars($link->getproductLink($product['id_product'], $product['link_rewrite'], Category::getLinkRewrite((int)($product['id_category_default']), $cookie->id_lang))).$affiliate."]]></link>\n";
		echo "\t\t</item>\n";
	}
?>
	</channel>
</rss>
