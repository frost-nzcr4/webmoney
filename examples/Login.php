<?php
	session_start();

	define('URL_UD',   '6d9e022e-210e-4b5e-b6f4-9dd7013674b2');
	define('PRIMARY_WMID',   '058016335779');

$dir = (version_compare(phpversion(), '5.3.0', '>=')) ? __DIR__ : dirname(__FILE__);
	require_once($dir . '/../src/webmoney/WMXILogin.php');
	$wmxi = new WMXILogin(URL_UD, PRIMARY_WMID, realpath('../WMXI.crt'));

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($wmid = $wmxi->AuthorizeWMID()) { $_SESSION['WMID'] = $wmid; }
	}

	if (empty($_SESSION['WMID'])) { $wmxi->Login(); }

	print('Logged in as: '. $_SESSION['WMID']);
?>