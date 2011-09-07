<?php
// if PHP lt 5.3
if (!defined('__DIR__')) {
  define('__DIR__', dirname(__FILE__));
}

if (file_exists(__DIR__ . '/authn.php')) {
	require_once (__DIR__ . '/authn.php');
} else {
	require_once (__DIR__ . '/authn.dist.php');
}
require_once (__DIR__ . '/../src/webmoney/WMXI.php');
?>