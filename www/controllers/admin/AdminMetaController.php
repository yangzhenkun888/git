<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class AdminMetaControllerCore extends AdminController
{
	public $table = 'meta';
	public $className = 'Meta';
	public $lang = true;
	protected $toolbar_scroll = false;

	public function __construct()
	{
		parent::__construct();

		$this->ht_file = _PS_ROOT_DIR_.'/.htaccess';
		$this->rb_file = _PS_ROOT_DIR_.'/robots.txt';
		$this->sm_file = _PS_ROOT_DIR_.'/sitemap.xml';
		$this->rb_data = $this->getRobotsContent();

		$this->explicitSelect = true;
		$this->addRowAction('edit');
		$this->addRowAction('delete');
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

		$this->fields_list = array(
			'id_meta' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'page' => array('title' => $this->l('Page'), 'width' => 120),
			'title' => array('title' => $this->l('Title'), 'width' => 120),
			'url_rewrite' => array('title' => $this->l('Friendly URL'), 'width' => 120)
		);
		$this->_group = 'GROUP BY a.id_meta';

		// Options to generate friendly urls
		$mod_rewrite = Tools::modRewriteActive();
		$general_fields = array(
			'PS_REWRITING_SETTINGS' => array(
				'title' => $this->l('Friendly URL'),
				'desc' => ($mod_rewrite ? $this->l('Enable only if your server allows URL rewriting (recommended)') : ''),
				'validation' => 'isBool',
				'cast' => 'intval',
				'type' => 'rewriting_settings',
				'mod_rewrite' => $mod_rewrite
			),
			'PS_CANONICAL_REDIRECT' => array(
				'title' => $this->l('Automatically redirect to Canonical URL'),
				'desc' => $this->l('Recommended, but your theme must be compliant'),
				'validation' => 'isBool',
				'cast' => 'intval',
				'type' => 'bool'
			),
		);

		$url_description = '';
		if ($this->checkConfiguration($this->ht_file))
			$general_fields['PS_HTACCESS_DISABLE_MULTIVIEWS'] = array(
				'title' => $this->l('Disable apache multiviews'),
				'desc' => $this->l('Enable this option only if you have problems with URL rewriting on some pages.'),
				'validation' => 'isBool',
				'cast' => 'intval',
				'type' => 'bool',
			);
		else
		{
			$url_description = $this->l('Before being able to use this tool, you need to:');
			$url_description .= '<br />- '.$this->l('create a blank .htaccess in your root directory');
			$url_description .= '<br />- '.$this->l('give it write permissions (CHMOD 666 on Unix system)');
		}

		// Options to generate robot.txt
		$robots_description = $this->l('Your robots.txt file MUST be in your website\'s root directory and nowhere else (e.g. http://www.yoursite.com/robots.txt).');
		if ($this->checkConfiguration($this->rb_file))
		{
			$robots_description .= '<br />'.$this->l('Generate your "robots.txt" file by clicking on the following button (this will erase your old robots.txt file):');
			$robots_submit = array('name' => 'submitRobots', 'title' => $this->l('Generate robots.txt file'));
		}
		else
		{
			$robots_description .= '<br />'.$this->l('Before being able to use this tool, you need to:');
			$robots_description .= '<br />- '.$this->l('create a blank robots.txt file in your root directory');
			$robots_description .= '<br />- '.$this->l('give it write permissions (CHMOD 666 on Unix system)');
		}

		$robots_options = array(
			'title' => $this->l('Robots file generation'),
			'description' => $robots_description,
		);

		if (isset($robots_submit))
			$robots_options['submit'] = $robots_submit;

		// Options for shop URL if multishop is disabled
		$shop_url_options = array(
			'title' => $this->l('Set shop URL'),
			'fields' => array(),
		);

		if (!Shop::isFeatureActive())
		{
			$this->url = ShopUrl::getShopUrls($this->context->shop->id)->where('main', '=', 1)->getFirst();
			if ($this->url)
			{
				$shop_url_options['description'] = $this->l('You can set here the URL for your shop. If you migrate your shop to a new URL, remember to change the values bellow.');
				$shop_url_options['fields'] = array(
					'domain' => array(
						'title' =>	$this->l('Shop domain'),
						'validation' => 'isString',
						'type' => 'text',
						'size' => 70,
						'defaultValue' => $this->url->domain,
					),
					'domain_ssl' => array(
						'title' =>	$this->l('SSL domain'),
						'validation' => 'isString',
						'type' => 'text',
						'size' => 70,
						'defaultValue' => $this->url->domain_ssl,
					),
					'uri' => array(
						'title' =>	$this->l('Base URI'),
						'validation' => 'isString',
						'type' => 'text',
						'size' => 70,
						'defaultValue' => $this->url->physical_uri,
					),
				);
			}
		}
		else
			$shop_url_options['description'] = $this->l('Multistore option is enabled, if you want to change the URL of your shop you have to go to "Multistore" page under the "Advanced Parameters"  menu.');

		// List of options
		$this->fields_options = array(
			'general' => array(
				'title' =>	$this->l('Set up URLs'),
				'description' => $url_description,
				'fields' =>	$general_fields,
				'submit' => array()
			),
			'shop_url' => $shop_url_options
		);

		// Add display route options to options form
		if (Configuration::get('PS_REWRITING_SETTINGS'))
		{
			$this->fields_options['routes'] = array(
				'title' =>	$this->l('Schema of URLs'),
				'description' => $this->l('Change the pattern of your links. There are some available keywords for each route listed below, keywords with * are required. To add a keyword in your URL use {keyword} syntax. You can add some text before or after the keyword IF the keyword is not empty with syntax {prepend:keyword:append}, for example {-hey-:meta_title} will add "-hey-my-title" in URL if meta title is set, or nothing. Friendly URL and rewriting Apache option must be activated on your web server to use this functionality.'),
				'fields' => array()
			);
			$this->addAllRouteFields();
		}

		$this->fields_options['robots'] = $robots_options;
	}

	public function initProcess()
	{
		parent::initProcess();
		// This is a composite page, we don't want the "options" display mode
		if ($this->display == 'options')
			$this->display = '';
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->addJqueryUi('ui.widget');
		$this->addJqueryPlugin('tagify');
	}

	public function addFieldRoute($route_id, $title)
	{
		$keywords = array();
		foreach (Dispatcher::getInstance()->default_routes[$route_id]['keywords'] as $keyword => $data)
			$keywords[] = ((isset($data['param'])) ? '<span class="red">'.$keyword.'*</span>' : $keyword);

		$this->fields_options['routes']['fields']['PS_ROUTE_'.$route_id] = array(
			'title' =>	$title,
			'desc' => sprintf($this->l('Keywords: %s'), implode(', ', $keywords)),
			'validation' => 'isString',
			'type' => 'text',
			'size' => 70,
			'defaultValue' => Dispatcher::getInstance()->default_routes[$route_id]['rule'],
		);
	}

	public function renderForm()
	{
		$files = Meta::getPages(true, ($this->object->page ? $this->object->page : false));
		$pages = array(
			'common' => array(
				'name' => $this->l('Default pages'),
				'query' => array(),
			),
			'module' => array(
				'name' => $this->l('Modules pages'),
				'query' => array(),
			),
		);

		foreach ($files as $name => $file)
		{
			$k = (preg_match('#^module-#', $file)) ? 'module' : 'common';
			$pages[$k]['query'][] = array(
				'id' => $file,
				'page' => $name,
			);
		}

 		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Meta-Tags'),
				'image' => '../img/admin/metatags.gif'
			),
			'input' => array(
				array(
					'type' => 'hidden',
					'name' => 'id_meta',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Page:'),
					'name' => 'page',

					'options' => array(
						'optiongroup' => array(
							'label' => 'name',
							'query' => $pages,
						),
						'options' => array(
							'id' => 'id',
							'name' => 'page',
							'query' => 'query',
						),
					),
					'desc' => $this->l('Name of the related page'),
					'required' => true,
					'empty_message' => '<p>'.$this->l('There is no page available!').'</p>',
				),
				array(
					'type' => 'text',
					'label' => $this->l('Page title:'),
					'name' => 'title',
					'lang' => true,
					'hint' => $this->l('Invalid characters:').' <>;=#{}',
					'desc' => $this->l('Title of this page'),
					'size' => 30
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta description:'),
					'name' => 'description',
					'lang' => true,
					'hint' => $this->l('Invalid characters:').' <>;=#{}',
					'desc' => $this->l('A short description of your shop'),
					'size' => 50
				),
				array(
					'type' => 'tags',
					'label' => $this->l('Meta keywords:'),
					'name' => 'keywords',
					'lang' => true,
					'hint' => $this->l('Invalid characters:').' <>;=#{}',
					'desc' => $this->l('List of keywords for search engines').' '.$this->l('To add "tags" click in the field, write something, then press "Enter"'), 
					'size' => 50
				),
				array(
					'type' => 'text',
					'label' => $this->l('Rewritten URL:'),
					'name' => 'url_rewrite',
					'lang' => true,
					'required' => true,
					'hint' => $this->l('Only letters and the minus (-) character are allowed'),
					'desc' => $this->l('e.g. "contacts" for http://mysite.com/shop/contacts to redirect to http://mysite.com/shop/contact-form.php'),
					'size' => 50
				),
			),
			'submit' => array(
				'title' => $this->l('   Save   '),
				'class' => 'button'
			)
		);
		return parent::renderForm();
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitAddmeta'))
		{
			$langs = Language::getLanguages(false);

			$default_language = Configuration::get('PS_LANG_DEFAULT');
			if (Tools::getValue('page') != 'index')
			{
				$defaultLangIsValidated = Validate::isLinkRewrite(Tools::getValue('url_rewrite_'.$default_language));
				$englishLangIsValidated = Validate::isLinkRewrite(Tools::getValue('url_rewrite_1'));
			}
			else
			{	// index.php can have empty rewrite rule
				$defaultLangIsValidated = !Tools::getValue('url_rewrite_'.$default_language) || Validate::isLinkRewrite(Tools::getValue('url_rewrite_'.$default_language));
				$englishLangIsValidated = !Tools::getValue('url_rewrite_1') || Validate::isLinkRewrite(Tools::getValue('url_rewrite_1'));
			}

			if (!$defaultLangIsValidated && !$englishLangIsValidated)
			{
				$this->errors[] = Tools::displayError('URL rewrite field must be filled at least in default or English language.');
				return false;
			}

			foreach ($langs as $lang)
			{
				$current = Tools::getValue('url_rewrite_'.$lang['id_lang']);
				if (strlen($current) == 0)
					// Prioritize default language first
					if ($defaultLangIsValidated)
						$_POST['url_rewrite_'.$lang['id_lang']] = Tools::getValue('url_rewrite_'.$default_language);
					else
						$_POST['url_rewrite_'.$lang['id_lang']] = Tools::getValue('url_rewrite_1');
			}

			Hook::exec('actionAdminMetaSave');
		}
		else if (Tools::isSubmit('submitRobots'))
			$this->generateRobotsFile();

		return parent::postProcess();
	}

	public function generateRobotsFile()
	{
		if (!$write_fd = @fopen($this->rb_file, 'w'))
			$this->errors[] = sprintf(Tools::displayError('Cannot write into file: %s. Please check write permissions.'), $this->rb_file);
		else
		{
			// PS Comments
			fwrite($write_fd, "# robots.txt automaticaly generated by MileBiz e-commerce open-source solution\n");
			fwrite($write_fd, "# http://www.milebiz.com - http://www.milebiz.com/forums\n");
			fwrite($write_fd, "# This file is to prevent the crawling and indexing of certain parts\n");
			fwrite($write_fd, "# of your site by web crawlers and spiders run by sites like Yahoo!\n");
			fwrite($write_fd, "# and Google. By telling these \"robots\" where not to go on your site,\n");
			fwrite($write_fd, "# you save bandwidth and server resources.\n");
			fwrite($write_fd, "# For more information about the robots.txt standard, see:\n");
			fwrite($write_fd, "# http://www.robotstxt.org/wc/robots.html\n");

			// User-Agent
			fwrite($write_fd, "User-agent: *\n");
			
			// Private pages
			if (count($this->rb_data['GB']))
			{
				fwrite($write_fd, "# Private pages\n");
				foreach ($this->rb_data['GB'] as $gb)
					fwrite($write_fd, 'Disallow: /*'.$gb."\n");
			}
			
			// Directories
			if (count($this->rb_data['Directories']))
			{
				fwrite($write_fd, "# Directories\n");
				foreach ($this->rb_data['Directories'] as $dir)
					fwrite($write_fd, 'Disallow: /*'.$dir."\n");
			}
			
			// Files
			if (count($this->rb_data['Files']))
			{
				fwrite($write_fd, "# Files\n");
				foreach ($this->rb_data['Files'] as $iso_code => $files)
					foreach ($files as $file)
						fwrite($write_fd, 'Disallow: /*'.$iso_code.'/'.$file."\n");
			}
			
			// Sitemap
			if (file_exists($this->sm_file) && filesize($this->sm_file))
			{
				fwrite($write_fd, "# Sitemap\n");
				fwrite($write_fd, 'Sitemap: '.(Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].__PS_BASE_URI__.'sitemap.xml'."\n");
			}

			fclose($write_fd);

			$this->redirect_after = self::$currentIndex.'&conf=4&token='.$this->token;
		}
	}

	public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, Context::getContext()->shop->id);
	}
	
	public function renderList()
	{
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
			$this->displayInformation($this->l('You can only display the page list in a shop context.'));
		else
			return parent::renderList();
	}

	/**
	 * Validate route syntax and save it in configuration
	 *
	 * @param string $route_id
	 */
	public function checkAndUpdateRoute($route_id)
	{
		$default_routes = Dispatcher::getInstance()->default_routes;
		if (!isset($default_routes[$route_id]))
			return;

		$rule = Tools::getValue('PS_ROUTE_'.$route_id);
		if (!$rule || $rule == $default_routes[$route_id]['rule'])
		{
			Configuration::updateValue('PS_ROUTE_'.$route_id, '');
			return;
		}

		$errors = array();
		if (!Dispatcher::getInstance()->validateRoute($route_id, $rule, $errors))
		{
			foreach ($errors as $error)
				$this->errors[] = sprintf('Keyword "{%1$s}" required for route "%2$s" (rule: "%3$s")', $error, $route_id, htmlspecialchars($rule));
		}
		else
			Configuration::updateValue('PS_ROUTE_'.$route_id, $rule);
	}

	/**
	 * Called when PS_REWRITING_SETTINGS option is saved
	 */
	public function updateOptionPsRewritingSettings()
	{
		Configuration::updateValue('PS_REWRITING_SETTINGS', (int)Tools::getValue('PS_REWRITING_SETTINGS'));
		Tools::generateHtaccess($this->ht_file, null, null, '', Tools::getValue('PS_HTACCESS_DISABLE_MULTIVIEWS'));

		Tools::enableCache();
		Tools::clearCache($this->context->smarty);
		Tools::restoreCacheSettings();
	}

	public function updateOptionPsRouteProductRule()
	{
		$this->checkAndUpdateRoute('product_rule');
	}

	public function updateOptionPsRouteCategoryRule()
	{
		$this->checkAndUpdateRoute('category_rule');
	}

	public function updateOptionPsRouteLayeredRule()
	{
		$this->checkAndUpdateRoute('layered_rule');
	}

	public function updateOptionPsRouteSupplierRule()
	{
		$this->checkAndUpdateRoute('supplier_rule');
	}

	public function updateOptionPsRouteManufacturerRule()
	{
		$this->checkAndUpdateRoute('manufacturer_rule');
	}

	public function updateOptionPsRouteCmsRule()
	{
		$this->checkAndUpdateRoute('cms_rule');
	}

	public function updateOptionPsRouteCmsCategoryRule()
	{
		$this->checkAndUpdateRoute('cms_category_rule');
	}

	/**
	 * Update shop domain (for mono shop)
	 */
	public function updateOptionDomain($value)
	{
		if (!Shop::isFeatureActive() && $this->url && $this->url->domain != $value)
		{
			if (Validate::isCleanHtml($value))
			{
				$this->url->domain = $value;
				$this->url->update();
			}
			else
				$this->errors[] = Tools::displayError('Domain is not valid');
		}
	}

	/**
	 * Update shop SSL domain (for mono shop)
	 */
	public function updateOptionDomainSsl($value)
	{
		if (!Shop::isFeatureActive() && $this->url && $this->url->domain_ssl != $value)
		{
			if (Validate::isCleanHtml($value))
			{
				$this->url->domain_ssl = $value;
				$this->url->update();
			}
			else
				$this->errors[] = Tools::displayError('SSL Domain is not valid');
		}
	}

	/**
	 * Update shop physical uri for mono shop)
	 */
	public function updateOptionUri($value)
	{
		if (!Shop::isFeatureActive() && $this->url && $this->url->physical_uri != $value)
		{
			$this->url->physical_uri = $value;
			$this->url->update();
		}
	}

	/**
	 * Function used to render the options for this controller
	 */
	public function renderOptions()
	{
		// If friendly url is not active, do not display custom routes form
		if (Configuration::get('PS_REWRITING_SETTINGS'))
			$this->addAllRouteFields();

		if ($this->fields_options && is_array($this->fields_options))
		{
			$helper = new HelperOptions($this);
			$this->setHelperDisplay($helper);
			$helper->toolbar_scroll = true;
			$helper->toolbar_btn = array('save' => array(
								'href' => '#',
								'desc' => $this->l('Save')
							));
			$helper->id = $this->id;
			$helper->tpl_vars = $this->tpl_option_vars;
			$options = $helper->generateOptions($this->fields_options);

			return $options;
		}
	}

	/**
	 * Add all custom route fields to the options form
	 */
	public function addAllRouteFields()
	{
		$this->addFieldRoute('product_rule', $this->l('Route to products'));
		$this->addFieldRoute('category_rule', $this->l('Route to category'));
		$this->addFieldRoute('layered_rule', $this->l('Route to category with attribute selected_filter for the module block layered'));
		$this->addFieldRoute('supplier_rule', $this->l('Route to supplier'));
		$this->addFieldRoute('manufacturer_rule', $this->l('Route to manufacturer'));
		$this->addFieldRoute('cms_rule', $this->l('Route to CMS page'));
		$this->addFieldRoute('cms_category_rule', $this->l('Route to CMS category'));
		$this->addFieldRoute('module', $this->l('Route to modules'));
	}

	/**
	 * Check if a file is writable
	 *
	 * @param string $file
	 * @return bool
	 */
	public function checkConfiguration($file)
	{
		if (file_exists($file))
			return is_writable($file);
		return is_writable(dirname($file));
	}

	public function getRobotsContent()
	{
		$tab = array();

		// Directories
		$tab['Directories'] = array('classes/', 'config/', 'download/', 'mails/', 'modules/', 'translations/', 'tools/');

		// Files
		$disallow_controllers = array(
			'addresses', 'address', 'authentication', 'cart', 'discount', 'footer',
			'get-file', 'header', 'history', 'identity', 'images.inc', 'init', 'my-account', 'order', 'order-opc',
			'order-slip', 'order-detail', 'order-follow', 'order-return', 'order-confirmation', 'pagination', 'password',
			'pdf-invoice', 'pdf-order-return', 'pdf-order-slip', 'product-sort', 'search', 'statistics','attachment', 'guest-tracking'
		);

		// Rewrite files
		$tab['Files'] = array();
		if (Configuration::get('PS_REWRITING_SETTINGS'))
		{
			$sql = 'SELECT ml.url_rewrite, l.iso_code
					FROM '._DB_PREFIX_.'meta m
					INNER JOIN '._DB_PREFIX_.'meta_lang ml ON ml.id_meta = m.id_meta
					INNER JOIN '._DB_PREFIX_.'lang l ON l.id_lang = ml.id_lang
					WHERE l.active = 1 AND m.page IN (\''.implode('\', \'', $disallow_controllers).'\')';
			if ($results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql))
				foreach ($results as $row)
					$tab['Files'][$row['iso_code']][] = $row['url_rewrite'];
		}

		$tab['GB'] = array(
			'orderby=','orderway=','tag=','id_currency=','search_query=','back=','utm_source=','utm_medium=','utm_campaign=','n='
		);

		foreach ($disallow_controllers as $controller)
			$tab['GB'][] = 'controller='.$controller;

		return $tab;
	}
}
