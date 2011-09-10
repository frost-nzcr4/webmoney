<?php
class PurseTest extends PHPUnit_Framework_TestCase {
	protected $Webmoney;
	protected $authn;

	protected function setUp() {
		$this->authn = WebmoneyAuthn::getAuthn();
		$this->Webmoney = new Webmoney($this->authn['cert']);
		$this->Webmoney->Light($this->authn['light']);
	}

	public static function InvalidDataProvider() {
		return array(
			array(
				'12345',
				'R12345'),
			array(
				'12345678901234567890',
				'R12345678901234567890'),
			array(
				'123456789012',
				'123456789012'),
			array(
				'123456789012',
				'L123456789012'),
			array(
				'123456789012',
				'R123456789012')
		);
	}

	/**
	 * @dataProvider InvalidDataProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidData($wmid, $purseId) {
		$Purse = new Purse($wmid, $purseId);
	}

	public function testValidData() {
		$wmid  = $this->authn['wmid'];
		$purse = $this->authn['purse'];

		$Purse = new Purse($wmid, $purse);
		$this->assertInstanceOf('Purse', $Purse);
		$this->assertEquals($purse, $Purse->getId());
		$this->assertEquals($wmid, $Purse->getWmid());

		$this->assertEquals(true, $Purse->isValid($this->Webmoney));
		//$this->assertEquals(true, $Purse->getResultX8());
	}
}
?>