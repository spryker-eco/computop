<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * @method \SprykerEco\Service\Computop\ComputopServiceFactory getFactory()
 */
class ComputopService extends AbstractService implements ComputopServiceInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->getHashHmacValue($this->getFactory()->createComputopMapper()->getMacEncryptedValue($cardPaymentTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopOrderDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->getFactory()->createComputopMapper()->getOrderDataEncryptedValue($cardPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopAuthorizationDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->getFactory()->createComputopMapper()->getAuthorizationDataEncryptedValue($cardPaymentTransfer);
    }

    /**
     * @param array $computopResponseArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    public function getComputopResponseTransfer($computopResponseArray)
    {
        $responseEncryptedString = $this->getFactory()->createBlowfish()->getBlowfishDecryptedValue(
            $computopResponseArray['Data'],
            $computopResponseArray['Len'],
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD)
        );

        return $this->getFactory()->createComputopConverter()->getComputopResponseTransfer($responseEncryptedString);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getHashHmacValue($value)
    {
        return $this->getFactory()->createHashHmac()->getHashHmacValue($value);
    }

    /**
     * @param string $plaintext
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishEncryptedValue($plaintext, $len, $password)
    {
        return $this->getFactory()->createBlowfish()->getBlowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @param string $cipher
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishDecryptedValue($cipher, $len, $password)
    {
        return $this->getFactory()->createBlowfish()->getBlowfishDecryptedValue($cipher, $len, $password);
    }

}
