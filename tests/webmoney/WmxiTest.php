<?php
class WmxiTest extends PHPUnit_Framework_TestCase {
	protected $Wmxi;

	protected function setUp() {
		$this->Wmxi = new WMXI();
		$this->Wmxi->Light(array('key' => '12345678901', 'cer' => '12345678901', 'pass' => '12345678901'));
	}

	public function testInvalidInterfaceX8() {
		$wmid  = '';
		$purse = '';

		$result = $this->Wmxi->X8($wmid, $purse);
		$this->assertInstanceOf('WMXIResult', $result);
		$this->assertEquals(58, $result->ErrorCode());
	}
}
?>
