<?php
class WmxiTest extends PHPUnit_Framework_TestCase {
	protected $Wmxi;
	protected $authn;

	protected function setUp() {
		$this->authn = WebmoneyAuthn::getAuthn();
		$this->Wmxi = new WMXI();
		$this->Wmxi->Light($this->authn['light']);
	}

	public function testX8InvalidArgs() {
		$wmid  = '';
		$purse = '';

		$result = $this->Wmxi->X8($wmid, $purse);
		$this->assertEquals(0, $result->ErrorCode());
	}

	public function testX8ValidWmid() {
	  $wmid  = $this->authn['publicWmids']['WmAttestationCenter'];
	  $purse = '';

	  // Search Webmoney Attestation Center.
	  $result = $this->Wmxi->X8($wmid, $purse);
	  $this->assertEquals(1, $result->ErrorCode());
	}
}
?>