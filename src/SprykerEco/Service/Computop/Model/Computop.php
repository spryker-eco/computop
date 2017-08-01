<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

class Computop implements ComputopInterface
{

    const MAC_SEPARATOR = '*';
    const DATA_SEPARATOR = '&';

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $macDataArray = [
            $cardPaymentTransfer->getPayId(),
            $cardPaymentTransfer->getTransId(),
            $cardPaymentTransfer->getMerchantId(),
            $cardPaymentTransfer->getAmount(),
            $cardPaymentTransfer->getCurrency(),
        ];

        return implode(self::MAC_SEPARATOR, $macDataArray);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $transID = "TransID=" . $cardPaymentTransfer->getTransId();
        $amount = "Amount=" . $cardPaymentTransfer->getAmount();
        $currency = "Currency=" . $cardPaymentTransfer->getCurrency();
        $urlSuccess = "URLSuccess=" . $cardPaymentTransfer->getUrlSuccess();
        $urlFailure = "URLFailure=" . $cardPaymentTransfer->getUrlFailure();
        $capture = "Capture=" . $cardPaymentTransfer->getCapture();
        $response = "Response=" . $cardPaymentTransfer->getResponse();
        $mac = "MAC=" . $cardPaymentTransfer->getMac();
        $txType = "TxType=" . $cardPaymentTransfer->getTxType();
        $orderDesc = "OrderDesc=" . $cardPaymentTransfer->getOrderDesc();

        $query = [$transID, $amount, $currency, $urlSuccess, $urlFailure, $capture, $response, $mac, $txType, $orderDesc];

        return implode(self::DATA_SEPARATOR, $query);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getAuthorizationDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $payId = "PayID=" . $cardPaymentTransfer->getPayId();
        $transID = "TransID=" . $cardPaymentTransfer->getTransId();
        $amount = "Amount=" . $cardPaymentTransfer->getAmount();
        $currency = "Currency=" . $cardPaymentTransfer->getCurrency();
        $capture = "Capture=" . $cardPaymentTransfer->getCapture();
        $response = "Response=" . $cardPaymentTransfer->getResponse();
        $mac = "MAC=" . $cardPaymentTransfer->getMac();
        $orderDesc = "OrderDesc=" . $cardPaymentTransfer->getOrderDesc();

        $query = [$payId, $transID, $amount, $currency, $capture, $response, $mac, $orderDesc];

        return implode(self::DATA_SEPARATOR, $query);
    }

}
