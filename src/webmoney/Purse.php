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