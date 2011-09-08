<?php
$dir = (version_compare(phpversion(), '5.3.0', '>=')) ? __DIR__ : dirname(__FILE__);

if (file_exists($dir . '/authn.php')) {
	require_once ($dir . '/authn.php');
} else {
	require_once ($dir . '/authn.dist.php');
}
require_once ($dir . '/../src/webmoney/WMXI.php');
// Reset after WMXI change $dir
$dir = (version_compare(phpversion(), '5.3.0', '>=')) ? __DIR__ : dirname(__FILE__);
require_once ($dir . '/../src/webmoney/Webmoney.php');
?>