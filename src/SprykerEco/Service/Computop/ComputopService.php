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
    public function getDescriptionValue(array $items)
    {
        return $this->getFactory()->createComputopMapper()->getDescriptionValue($items);
    }

    /**
     * @inheritdoc
     */
    public function getMacEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $value = $this->getFactory()->createComputopMapper()->getMacEncryptedValue($cardPaymentTransfer);
        return $this->getHashHmacValue($value);
    }

    /**
     * @inheritdoc
     */
    public function extractHeader(array $decryptedArray, $method)
    {
        /** @var \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header */
        $header = $this->getFactory()->createComputopConverter()->extractHeader($decryptedArray, $method);

        $neededMac = $this->getHashHmacValue($this->getFactory()->createComputopMapper()->getMacResponseEncryptedValue($header));
        $this
            ->getFactory()
            ->createComputopConverter()
            ->checkMacResponse($header->getMac(), $neededMac, $header->getMethod());

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
            $responseArray[ComputopConstants::LENGTH_F_N],
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
        $length = strlen($plaintext);

        $encryptedArray[ComputopConstants::DATA_F_N] = $this->getBlowfishEncryptedValue(
            $plaintext,
            $length,
            $password
        );

        $encryptedArray[ComputopConstants::LENGTH_F_N] = $length;

        return $encryptedArray;
    }

    /**
     * @inheritdoc
     */
    public function getHashHmacValue($value)
    {
        return $this->getFactory()->createHmacHasher()->getEncryptedValue($value);
    }

    /**
     * @inheritdoc
     */
    public function getBlowfishEncryptedValue($plaintext, $length, $password)
    {
        return $this->getFactory()->createBlowfishHasher()->getBlowfishEncryptedValue($plaintext, $length, $password);
    }

    /**
     * @inheritdoc
     */
    public function getBlowfishDecryptedValue($cipher, $length, $password)
    {
        return $this->getFactory()->createBlowfishHasher()->getBlowfishDecryptedValue($cipher, $length, $password);
    }

}
