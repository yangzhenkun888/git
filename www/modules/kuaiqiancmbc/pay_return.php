<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/kuaiqiancmbc.php');
$kuaiqiancmbc = new KuaiqianCmbc();
$redirectLink = '';
$kuaiqiancmbc->analyzeReturn($redirectLink);
Tools::redirect($redirectLink);

?>