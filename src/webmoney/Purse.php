<?php
/**
 * Webmoney purse.
 *
 * @package Webmoney
 * @author  frost-nzcr4 <frost-nzcr4@github.com>
 */
class Purse {
	/**
	 * Purse id.
	 *
	 * @var string
	 */
	private $id = '';

	/**
	 * Is purse valid.
	 *
	 * @var boolean
	 */
	private $isValid = false;

	/**
	 * Result of X8 interface call.
	 *
	 * @var SimpleXMLElement
	 */
	private $resultX8 = null;

	/**
	 * Purse type (WMZ, WMR, etc).
	 *
	 * @var string
	 */
	private $type = '';

	/**
	 * WMID.
	 *
	 * @var string
	 */
	private $wmid = '';

	/**
	 * Construct the purse with given Purse ID or Purse ID and WMID.
	 *
	 * @param string purseId
	 * @param mixed  wmid
	 * @throws InvalidArgumentException
	 */
	public function __construct($purseId, $wmid = '') {
		if (!self::testId($purseId)) {
			ob_start();
			echo '$purseId=';
			var_dump($purseId);
			throw new InvalidArgumentException('Argument $purseId is invalid. ' . ob_get_clean());
		}
		if ('' !== $wmid) {
			if (!(self::testWmid($wmid) && self::testWmidAndPurse($wmid, $purseId))) {
			  ob_start();
			  echo '$wmid=';
			  var_dump($wmid);
			  echo '$purseId=';
			  var_dump($purseId);

			  throw new InvalidArgumentException('Arguments $purseId and/or $wmid are invalid. ' . ob_get_clean());
			}
		}

		$this->id   = $purseId;
		$this->type = 'WM' . substr($purseId, 0, 1);
		$this->wmid = $wmid;
	}

	/**
	 * Check if purse is valid.
	 *
	 * @return boolean
	 */
	public function isValid(Webmoney &$Webmoney) {
		// :TODO: Purse should have access to WMXI::X8?
		if (is_null($this->resultX8)) {
			$this->resultX8 = $Webmoney->X8($this->getWmid(), $this->getId());
			if (1 === $this->resultX8->ErrorCode()) {
			  $this->isValid = true;
			}
		}

		return $this->isValid;
	}

	/**
	 * Test for valid Purse ID.
	 *
	 * @param mixed $purseId
	 * @return boolean
	 */
	public static function testId($purseId) {
		$purseId = strtoupper((string) $purseId);

		if (preg_match('/^[BCDEGRUYZ][0-9]{12}$/', $purseId)) {
			return true;
		}

		return false;
	}

	/**
	 * Test for valid WMID.
	 *
	 * @param mixed $wmid
	 * @return boolean
	 */
	public static function testWmid($wmid) {
		$wmid = (string) $wmid;

		if (preg_match('/^[0-9]{12}$/', $wmid)) {
			return true;
		}

		return false;
	}

	/**
	 * Test for valid WMID & Purse ID.
	 *
	 * @param mixed $wmid
	 * @param mixed $purseId
	 * @return bool
	 */
	public static function testWmidAndPurse($wmid, $purseId) {
		$wmid    = (string) $wmid;
		$purseId = (string) $purseId;

		if ($wmid !== substr($purseId, 1)) {
			return true;
		}

		return false;
	}

	/* Getters */

	/**
	 * Get Purse ID.
	 *
	 * @return string
	 */
	public function getId() {
	  return $this->id;
	}

	/**
	 * Get result of X8 interface call.
	 *
	 * @return SimpleXMLElement
	 */
	public function getResultX8() {
		return $this->resultX8;
	}

	/**
	 * Get purse type.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get WMID.
	 *
	 * @return string
	 */
	public function getWmid() {
	  return $this->wmid;
	}
}
?>