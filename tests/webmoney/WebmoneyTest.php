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
		$authn   = WebmoneyAuthn::getAuthn();
		$wmid    = $authn['wmid'];
		$purseId = $authn['purse'];
		$srcPurse = new Purse($purseId, $wmid);

		$wmid    = $authn['wmid'];
		$purseId = $authn['purse'];
		$dstPurse = new Purse($purseId, $wmid);

		$amount_pass = 0.01;
		$amount_fail1 = 0.001;
		$amount_fail2 = -1;

		$period_pass = 0;
		$period_fail1 = 256;
		$period_fail2 = -1;

		$pcode_pass1  = '';
		$pcode_pass2  = 'my protection code';
		$pcode_fail1 = '';
		$pcode_fail2 = 'my protection code when $period=0';
		$pcode_fail3 = str_repeat('a', 256);
		$pcode_fail4 = ' leading space';
		$pcode_fail5 = 'space at the end ';

		return array(
	  	array($srcPurse, $srcPurse, 0, $amount_pass, $period_pass, '', '', 0, 0),
			array($srcPurse, $srcPurse, 0, -1, 0, '', '', 0, 0)
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

		$wmid    = '';
		$purseId = $this->authn['publicPurses']['somebody'];
		$dstPurse = new Purse($purseId, $wmid);

		$result = $this->Webmoney->transferFunds($srcPurse, $dstPurse, 0, 0.01, 0, '', 'Test', 0, 1);
		$this->assertEquals(999, $result);
	}
}
?>