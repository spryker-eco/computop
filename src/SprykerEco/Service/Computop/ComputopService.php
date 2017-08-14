<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Service\Kernel\AbstractService;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * @method \SprykerEco\Service\Computop\ComputopServiceFactory getFactory()
 */
class ComputopService extends AbstractService implements ComputopServiceInterface
{

    /**
     * @inheritdoc
     */
    public function getComputopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $value = $this->getFactory()->createComputopMapper()->getMacEncryptedValue($cardPaymentTransfer);
        return $this->getHashHmacValue($value);
    }

    /**
     * @inheritdoc
     */
    public function extractHeader(array $decryptedArray)
    {
        $header = $this->getFactory()->createComputopConverter()->extractHeader($decryptedArray);
        $neededMac = $this->getHashHmacValue($this->getFactory()->createComputopMapper()->getMacResponseEncryptedValue($header));
        $this
            ->getFactory()
            ->createComputopConverter()
            ->checkMacResponse($header->getMac(), $neededMac);

        return $header;
    }

    /**
     * @inheritdoc
     */
    public function getDecryptedArray(array $responseArray, $password)
    {
        $this
            ->getFactory()
            ->createComputopConverter()
            ->checkEncryptedResponse($responseArray);

        $responseDecryptedString = $this->getBlowfishDecryptedValue(
            $responseArray[ComputopConstants::DATA_F_N],
            $responseArray[ComputopConstants::LEN_F_N],
            $password
        );

        $responseDecryptedArray = $this
            ->getFactory()
            ->createComputopConverter()
            ->getResponseDecryptedArray($responseDecryptedString);

        return $responseDecryptedArray;
    }

    /**
     * @inheritdoc
     */
    public function getEncryptedArray(array $dataSubArray, $password)
    {
        $plaintext = $this->getFactory()->createComputopMapper()->getDataPlaintext($dataSubArray);
        $len = strlen($plaintext);

        $encryptedArray[ComputopConstants::DATA_F_N] = $this->getBlowfishEncryptedValue(
            $plaintext,
            $len,
            $password
        );

        $encryptedArray[ComputopConstants::LEN_F_N] = $len;

        return $encryptedArray;
    }

    /**
     * @inheritdoc
     */
    public function getHashHmacValue($value)
    {
        return $this->getFactory()->createHashHmac()->getHashHmacValue($value);
    }

    /**
     * @inheritdoc
     */
    public function getBlowfishEncryptedValue($plaintext, $len, $password)
    {
        return $this->getFactory()->createBlowfish()->getBlowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @inheritdoc
     */
    public function getBlowfishDecryptedValue($cipher, $len, $password)
    {
        return $this->getFactory()->createBlowfish()->getBlowfishDecryptedValue($cipher, $len, $password);
    }

}
