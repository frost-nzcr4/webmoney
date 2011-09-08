<?php
/**
 * Secret Webmoney authentication options
 */
class WebmoneyAuthn {
	/**
	 * Get authentication options.
	 *
	 * @return array
	 */
	public static function getAuthn() {
		$dir = (version_compare(phpversion(), '5.3.0', '>=')) ? __DIR__ : dirname(__FILE__);

		$webmoney_authn = array(
			'wmid'  => '123456789012',
			'purse' => 'R123456789019',
			'cert'  => realpath($dir . '/../cert/WebMoneyCA.crt'),

			// Webmoney Keeper Light
			'light' => array(
				'key'  => realpath($dir . '/../keys/webmoney-light.key'),
				'cer'  => realpath($dir . '/../keys/webmoney-light.cer'),
				'pass' => 'my-secret-password'
			),

			'publicWmids' => array(
				'WmAttestationCenter' => '464889785562'
			)
		);

		return $webmoney_authn;
	}
}
?>