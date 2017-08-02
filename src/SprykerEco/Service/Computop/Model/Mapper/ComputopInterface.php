<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

interface ComputopInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getOrderDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getAuthorizationDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

}
