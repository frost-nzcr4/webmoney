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
				'R12345',
				'12345'),
			array(
				'R12345678901234567890',
				'12345678901234567890'),
			array(
				'123456789012',
				'123456789012'),
			array(
				'L123456789012',
				'123456789012'),
			array(
				'R123456789012',
				'123456789012'),
			array(
				'R12345',
				''),
			array(
				'R12345678901234567890',
				''),
			array(
				'123456789012',
				''),
			array(
				'L123456789012',
				'')
		);
	}

	/**
	 * @dataProvider InvalidDataProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidData($purseId, $wmid) {
		$Purse = new Purse($purseId, $wmid);
	}

	public function testValidData() {
		$wmid  = $this->authn['wmid'];
		$purse = $this->authn['purse'];

		$Purse = new Purse($purse, $wmid);
		$this->assertInstanceOf('Purse', $Purse);
		$this->assertEquals($purse, $Purse->getId());
		$this->assertEquals($wmid, $Purse->getWmid());

		$Purse = new Purse($purse, '');
		$this->assertInstanceOf('Purse', $Purse);
		$this->assertEquals($purse, $Purse->getId());
		$this->assertEquals('', $Purse->getWmid());

		$Purse->isValid($this->Webmoney);
		$this->assertEquals(true, $Purse->isValid($this->Webmoney));
		//$this->assertEquals(true, $Purse->getResultX8());
	}
}
?>