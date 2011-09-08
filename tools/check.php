<?php
$dir = (version_compare(phpversion(), '5.3.0', '>=')) ? __DIR__ : dirname(__FILE__);

header('Content-Type: text/plain;');
print("\n");
print('Webmoney config check'."\n");
print('============================='."\n\n");

	if (file_exists($dir . '/../src/webmoney/MD4.php')) { include_once ($dir . '/../src/webmoney/MD4.php'); }

	define('PASSED', 'passed [+]');
	define('FAILED', 'failed [-]');

	$md4a = class_exists('MD4');
	$md4b = extension_loaded('mhash');
	$md4c = extension_loaded('hash');
	$md4  = $md4a | $md4b | $md4c;

	$matha = extension_loaded('bcmath');
	$mathb = extension_loaded('gmp');
	$math  = $matha | $mathb;

	$mba = extension_loaded('mbstring');
	$mbb = extension_loaded('iconv');
	$mb  = $mba | $mbb;

	$curl = extension_loaded('curl');

	$xml  = extension_loaded('SimpleXML');

	$light   = $mb & $curl & $xml;
	$classic = $md4 & $math & $light;

	print("------------ MD4 ------------\n");
	print("   MD4 Class  : " . ($md4a ? PASSED : FAILED) . "   \n");
	print("   MHash      : " . ($md4b ? PASSED : FAILED) . "   \n");
	print("   Hash       : " . ($md4c ? PASSED : FAILED) . "   \n");
	print(" > Overall    : " . ($md4  ? PASSED : FAILED) . " < \n");
	print("\n");

	print("--------- Huge math ---------\n");
	print("   BCMath     : " . ($matha ? PASSED : FAILED) . "   \n");
	print("   GMP        : " . ($mathb ? PASSED : FAILED) . "   \n");
	print(" > Overall    : " . ($math  ? PASSED : FAILED) . " < \n");
	print("\n");


	print("----- Multibyte strings -----\n");
	print("   MBString   : " . ($mba ? PASSED : FAILED) . "   \n");
	print("   iconv      : " . ($mbb ? PASSED : FAILED) . "   \n");
	print(" > Overall    : " . ($mb  ? PASSED : FAILED) . " < \n");
	print("\n");

	print("-----------  cURL -----------\n");
	print(" > cURL       : " . ($curl ? PASSED : FAILED) . " < \n");
	print("\n");

	print("--------- SimpleXML ---------\n");
	print(" > SimpleXML  : " . ($xml ? PASSED : FAILED) . " < \n");
	print("\n");

	print("-- WebMoney Keeper Classic --\n");
	print("   MD4        : " . ($md4     ? PASSED : FAILED) . "   \n");
	print("   Huge math  : " . ($math    ? PASSED : FAILED) . "   \n");
	print("   MB Strings : " . ($mb      ? PASSED : FAILED) . "   \n");
	print("   cURL       : " . ($curl    ? PASSED : FAILED) . "   \n");
	print("   SimpleXML  : " . ($xml     ? PASSED : FAILED) . "   \n");
	print(" > Overall    : " . ($classic ? PASSED : FAILED) . " < \n");
	print("\n");

	print("--- WebMoney Keeper Light ---\n");
	print("   MB Strings : " . ($mb      ? PASSED : FAILED) . "   \n");
	print("   cURL       : " . ($curl    ? PASSED : FAILED) . "   \n");
	print("   SimpleXML  : " . ($xml     ? PASSED : FAILED) . "   \n");
	print(" > Overall    : " . ($light   ? PASSED : FAILED) . " < \n");
	print("\n");

// Optional checks
// array_fill_keys (PHP >= 5.2.0)
$optional_keys = array('PHP', 'PHPUnit');
$check = array(
	'optional' => array_fill_keys($optional_keys, false)
);

$check['optional']['PHP']['msg'] = 'PHP version >= 5.3.0';
if (version_compare(phpversion(), '5.3.0', '>=')) {
	$check['optional']['PHP']['pass'] = true;
} else {
	$check['optional']['PHP']['msg'] .= ' (PHP ' . phpversion() . ' is installed)';
}

$check['optional']['PHPUnit']['msg'] = 'PHPUnit is installed';
include_once ('PHPUnit/Autoload.php');
if (class_exists('PHPUnit_Framework_TestCase')) {
	$check['optional']['PHPUnit']['pass'] = true;
} else {
	$check['optional']['PHPUnit']['msg'] .= ' (https://github.com/sebastianbergmann/phpunit)';
}

// Output
print('Optional'."\n");
print('-----------------------------'."\n");
foreach ($check['optional'] as $key => $val) {
	print(($val['pass'] ? '[+]   ' : '[fail]') . ' ' . $val['msg'] ."\n");
}
print("\n");
?>