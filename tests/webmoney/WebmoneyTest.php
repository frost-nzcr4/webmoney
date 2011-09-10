<?php
class WebmoneyTest extends PHPUnit_Framework_TestCase {
	protected $Webmoney;
	protected $authn;

	protected function setUp() {
		$this->authn = WebmoneyAuthn::getAuthn();
		$this->Webmoney = new Webmoney($this->authn['cert']);
		$this->Webmoney->Light($this->authn['light']);
	}

	public static function InvalidDataProvider() {
		$wmid    = $this->authn['wmid'];
		$purseId = $this->authn['purse'];
		$srcPurse = new Purse($purseId, $wmid);

		$wmid    = $this->authn['wmid'];
		$purseId = $this->authn['purse'];
		$dstPurse = new Purse($purseId, $wmid);

		return array(
	  	array(
				$srcPurse,
				$dstPurse,
				0,
				0.001,
				0)
	  );
	}

	/**
	 * @dataProvider InvalidDataProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidData($srcPurse, $dstPurse, $tranid, $amount, $period, $pcode, $desc, $wminvid, $onlyauth) {
	  $this->Webmoney->transferFunds($srcPurse, $dstPurse, $tranid, $amount, $period, $pcode, $desc, $wminvid, $onlyauth);
	}

	public function testTransferFunds() {
		$wmid    = $this->authn['wmid'];
		$purseId = $this->authn['purse'];
		$srcPurse = new Purse($purseId, $wmid);

		// Search yourself by your WMID & Purse ID.
		$result = $this->Webmoney->X8($srcPurse->getWmid(), $srcPurse->getId());
		$this->assertEquals(1, $result->ErrorCode());

		$wmid    = $this->authn['wmid'];
		$purseId = $this->authn['purse'];
		$dstPurse = new Purse($purseId, $wmid);

		// Search destination purse by WMID & Purse ID.
		$result = $this->Webmoney->X8($dstPurse->getWmid(), $dstPurse->getId());
		$this->assertEquals(1, $result->ErrorCode());
		$this->assertEquals(123, (int) $result->toObject()->testwmpurse->wmid->attributes()->available);
		$this->assertEquals(123, (int) $result->toObject()->testwmpurse->wmid->attributes()->themselfcorrstate);
		$this->assertEquals(1, $result);

		$result = $this->Webmoney->transferFunds($srcPurse, $dstPurse, 0.001);
		$this->assertEquals(999, $result);
	}
}
?>