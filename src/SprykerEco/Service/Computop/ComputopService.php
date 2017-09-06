<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractService;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * @method \SprykerEco\Service\Computop\ComputopServiceFactory getFactory()
 */
class ComputopService extends AbstractService implements ComputopServiceInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items)
    {
        return $this->getFactory()->createComputopMapper()->getDescriptionValue($items);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(AbstractTransfer $cardPaymentTransfer)
    {
        $value = $this->getFactory()->createComputopMapper()->getMacEncryptedValue($cardPaymentTransfer);
        return $this->getHashValue($value);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray, $method)
    {
        /** @var \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header */
        $header = $this->getFactory()->createComputopConverter()->extractHeader($decryptedArray, $method);

        $neededMac = $this->getHashValue($this->getFactory()->createComputopMapper()->getMacResponseEncryptedValue($header));
        $this
            ->getFactory()
            ->createComputopConverter()
            ->checkMacResponse($header->getMac(), $neededMac, $header->getMethod());

        return $header;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $responseArray
     * @param string $password
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return array
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $dataSubArray
     * @param string $password
     *
     * @return array
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $value
     *
     * @return string
     */
    public function getHashValue($value)
    {
        return $this->getFactory()->createHmacHasher()->getEncryptedValue($value);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $plaintext
     * @param int $length
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishEncryptedValue($plaintext, $length, $password)
    {
        return $this->getFactory()->createBlowfishHasher()->getBlowfishEncryptedValue($plaintext, $length, $password);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $cipher
     * @param int $length
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishDecryptedValue($cipher, $length, $password)
    {
        return $this->getFactory()->createBlowfishHasher()->getBlowfishDecryptedValue($cipher, $length, $password);
    }

}
