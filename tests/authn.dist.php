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
		$webmoney_authn = array(
			'wmid'  => '123456789012',
			'purse' => 'R123456789019',
			'cert'  => realpath(__DIR__.'/../cert/WebMoneyCA.crt'),

			// Webmoney Keeper Light
			'light' => array(
				'key'  => realpath(__DIR__.'/../keys/webmoney-light.key'),
				'cer'  => realpath(__DIR__.'/../keys/webmoney-light.cer'),
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