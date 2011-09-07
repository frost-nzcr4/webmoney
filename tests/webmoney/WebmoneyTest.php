<?php
class WebmoneyTest extends PHPUnit_Framework_TestCase {
	protected $Webmoney;
	protected $authn;

	protected function setUp() {
		$this->authn = WebmoneyAuthn::getAuthn();
		$this->Webmoney = new Webmoney();
		$this->Webmoney->Light($this->authn['light']);
	}

	public function testTransferFunds() {
		$wmid    = $this->authn['wmid'];
		$purseId = $this->authn['purse'];
		$sourcePurse = new Purse($wmid, $purseId);

		// Search yourself by your WMID & Purse ID.
		$result = $this->Webmoney->X8($wmid, $purseId);

		$wmid  = $this->authn['publicWmids']['WmAttestationCenter'];
		//$purse = $this->authn['purse'];

		// Search Webmoney Attestation Center.
		$result = $this->Webmoney->X8($wmid);

		$this->assertEquals(1, $result->ErrorCode());
		$this->assertEquals(58, $result);

		$result = $this->Webmoney->transferFunds($wmid, $purseId);
		$this->assertEquals(999, $result);
	}
}
?>
