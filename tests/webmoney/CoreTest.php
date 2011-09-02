<?php
class CoreTest extends PHPUnit_Framework_TestCase {
	protected $Wmxi;

	protected function setUp() {
		$this->Wmxi = new WMXI();
	}

	public function testConvertAmountToProperFormat() {
		$amount = 10.50;
		$this->Wmxi->convertAmountToProperFormat($amount);
		$this->assertEquals(10.5, $amount);

		$amount = '10.50';
		$this->Wmxi->convertAmountToProperFormat($amount);
		$this->assertEquals(10.5, $amount);

		$amount = '10,20';
		$this->Wmxi->convertAmountToProperFormat($amount);
		$this->assertEquals(10.2, $amount);

		$amount = '10.0';
		$this->Wmxi->convertAmountToProperFormat($amount);
		$this->assertEquals(10, $amount);
	}
}
?>