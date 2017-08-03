<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Service\Kernel\AbstractService;

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
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader($decryptedArray)
    {
        return $this->getFactory()->createComputopConverter()->extractHeader($decryptedArray);
    }

    /**
     * @param array $responseArray
     * @param string $password
     *
     * @return array
     */
    public function getDecryptedArray($responseArray, $password)
    {
        $responseDecryptedString = $this->getBlowfishDecryptedValue(
            $responseArray['Data'],
            $responseArray['Len'],
            $password
        );

        $responseDecryptedArray = $this
            ->getFactory()
            ->createComputopConverter()
            ->getResponseDecryptedArray($responseDecryptedString);

        return $responseDecryptedArray;
    }

    /**
     * @param array $dataSubArray
     * @param string $password
     *
     * @return array
     */
    public function getEncryptedArray($dataSubArray, $password)
    {
        $plaintext = $this->getFactory()->createComputopMapper()->getDataPlaintext($dataSubArray);
        $len = strlen($plaintext);

        $encryptedArray['Data'] = $this->getBlowfishEncryptedValue(
            $plaintext,
            $len,
            $password
        );

        $encryptedArray['Len'] = $len;

        return $encryptedArray;
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
