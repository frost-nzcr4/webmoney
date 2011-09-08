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
	public function transferFunds(Purse $src, Purse $dst, $tranid, $amount, $period, $pcode, $desc, $wminvid, $onlyauth) {
		if ($src == $dst) {
			throw new InvalidArgumentException('Arguments $src & $dst should be different');
		}

		if ($src->getType() !== $dst->getType()) {
		  throw new InvalidArgumentException('Arguments $src & $dst should have the same purse type');
		}

		// test amount
		if (!is_int($amount) && !is_float($amount)) {
		  $this->convertAmountToProperFormat($amount);
		}
		$amount_min = 0.01;
		switch ($src->getType()) {
			case 'WMY':
				$amount_min = 1000;
				break;

			case 'WMB':
				$amount_min = 2000;
				break;
		}
		if ($amount < $amount_min) {
			throw new InvalidArgumentException('Argument $amount shouldn\'t be less than ' . $amount_min . ', given is ' . $amount);
		}

		// test period
		if (!is_numeric($period)) {
			throw new InvalidArgumentException('Argument $period should be between 0 and 255, given is ' . $period);
		} else {
			$period = intval($period);
			if ($period < 0 || $period > 255) {
				throw new InvalidArgumentException('Argument $period should be between 0 and 255, given is ' . $period);
			}
		}

		// test pcode
		if (!is_string($pcode)) {
		  throw new InvalidArgumentException('Argument $pcode should be a string, but given is "' . $pcode . '"');
		} else {
		  $len = strlen($pcode);
		  if ($len > 255) {
		    throw new InvalidArgumentException('Argument $pcode should be less than or equal to 255, given is (' . $len . ') "' . $pcode . '"');
		  }
		  if (' ' === substr($pcode, 0, 1) || ' ' === substr($pcode, -1)) {
		  	throw new InvalidArgumentException('Argument $pcode shouldn\'t contain spaces at the beginning or the end, given is "' . $pcode . '"');
		  }
		}

		// test desc
		if (!is_string($desc)) {
		  throw new InvalidArgumentException('Argument $desc should be a string, but given is "' . $desc . '"');
		} else {
		  $len = strlen($desc);
		  if ($len > 255) {
		    throw new InvalidArgumentException('Argument $desc should be less than or equal to 255, given is (' . $len . ') "' . $desc . '"');
		  }
		  if (' ' === substr($desc, 0, 1) || ' ' === substr($desc, -1)) {
		    throw new InvalidArgumentException('Argument $desc shouldn\'t contain spaces at the beginning or the end, given is "' . $desc . '"');
		  }
		}

		// test wminvid
		if (!is_numeric($wminvid)) {
		  throw new InvalidArgumentException('Argument $wminvid should be between 0 and 255, given is ' . $wminvid);
		} else {
		  $wminvid = intval($wminvid);
		  $max = (pow(2, 32) - 1);
		  if ($wminvid < 0 || $wminvid > $max) {
		    throw new InvalidArgumentException('Argument $wminvid should be between 0 and ' . $max . ', given is ' . $wminvid);
		  }
		}

		// test onlyauth
		if (!is_numeric($onlyauth)) {
		  throw new InvalidArgumentException('Argument $onlyauth should be 0 or 1, given is ' . $onlyauth);
		} else {
			$onlyauth = intval($onlyauth);
			if ($onlyauth < 0 || $onlyauth > 1) {
				throw new InvalidArgumentException('Argument $onlyauth should be 0 or 1, given is ' . $onlyauth);
			}
		}

		$result_src = $this->X8($src->getWmid(), $src->getId());
		if (1 !== $result_src->ErrorCode()) {
			return array(false, $result_src);
		}

		// :TODO: should it be tested within purse?
		if (
			$src->getWmid() !== strval($result_src->toObject()->testwmpurse->wmid)
			||
			$src->getId() !== strval($result_src->toObject()->testwmpurse->purse)) {
			return array(false, $result_src);
		}

		$result_dst = $this->X8($dst->getWmid(), $dst->getId());
		if (1 !== $result_dst->ErrorCode()) {
		  return array(false, $result_dst, $result_src);
		}

		if (1 === (int) $result_dst->toObject()->testwmpurse->wmid->attributes()->available) {
			// The available attribute, if “1”, means that ALL incoming operations (direct payments,
			// invoice payments, merchant.webmoney payments, X2 interface payments) are forbidden for ALL
			// purses for the WM identifier that has been searched for.
			return array(false, $result_dst, $result_src);
		}

		if (8 === (int) $result_dst->toObject()->testwmpurse->wmid->attributes()->themselfcorrstate) {
			// The themselfcorrstate attribute is a decimal representation of whether the user for the WM
			// identifier that is being searched for has allowed or forbidden the acceptance of payments,
			// messages and invoices from NONcorrespondents. Payments are indicated by the fourth bit
			// from the right, so the decimal value 0 (binary 0000) means that the user has not set any
			// restrictions, while the value of 8 (binary 1000) means that the user has forbidden
			// incoming payments to the user’s purses from NONcorrespondents.
			return array(false, $result_dst, $result_src);
		}

		if (1 === (int) $result_dst->toObject()->testwmpurse->purse->attributes()->merchant_allow_cashier) {
		  // For the merchant_allow_cashier attribute, “1” means that for the purse in question,
		  // payment acceptance through cash terminals to merchant.webmoney is enabled, and making
		  // direct payments to the purse (including through the X2 interface) is forbidden, and
		  // payments may be made to the purse only if the WMID has issued an invoice or through
		  // merchant.webmoney.
		  return array(false, $result_dst, $result_src);
		}

		//$result->GetResponse()->testwmpurse;

		//return true;
		$result = $this->X2($tranid, $src->getId(), $dst->getId(), $amount, $period, $pcode, $desc, $wminvid, $onlyauth);
		if (0 === $result->ErrorCode()) {
			// return true;
		}
		return array(true, $result, $result_dst, $result_src);
	}
}
?>