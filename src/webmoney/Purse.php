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
	 * @var bool
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
	 * Construct the purse with given WMID and PurseID.
	 *
	 * @param mixed  $wmid
	 * @param string $purseId
	 * @throws InvalidArgumentException
	 */
	public function __construct($wmid, $purseId) {
		if (!(self::testWmid($wmid) && self::testId($purseId) && self::testWmidAndPurse($wmid, $purseId))) {
			ob_start();
			echo '$wmid=';
			var_dump($wmid);
			echo '$purseId=';
			var_dump($purseId);

			throw new InvalidArgumentException(ob_get_clean());
		}

		$this->wmid = $wmid;
		$this->id   = $purseId;
		$this->type = 'WM' . substr($purseId, 0, 1);
	}

	/**
	 * Check if purse is valid.
	 *
	 * @return bool
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
	 * @return bool
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
	 * @return bool
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