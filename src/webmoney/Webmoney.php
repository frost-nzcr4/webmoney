<?php
/**
 * Webmoney Transfer payment system.
 *
 * @package Webmoney
 * @author  frost-nzcr4 <frost-nzcr4@github.com>
 */
class Webmoney extends WMXI {
	/**
	 * Transfer funds from one purse to another.
	 *
	 * Transfer by using X8 & X2 XML interfaces. X8 used to check destination
	 * purse that can accept funds transferring.
	 *
	 * @param Purse src    Source purse.
	 * @param Purse dst    Destination purse.
	 * @param int   amount Amount of the sum transferred.
	 * @return bool
	 */
	public function transferFunds(Purse $src, Purse $dst, $amount) {
		$result = $this->X8($src->getWmid(), $src->getId());
		if (1 !== $result->ErrorCode()) {
			return false;
		}

		$result = $this->X8($dst->getWmid(), $dst->getId());
		if (1 !== $result->ErrorCode()) {
		  return false;
		}

		return true;
		//$this->X2($tranid, $src->getId(), $dst->getId(), $amount, $period, $pcode, $desc, $wminvid, $onlyauth)
	}
}
?>