<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Spryker\Service\Kernel\AbstractService;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

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
    public function getTestModeDescriptionValue(array $items)
    {
        return $this->getFactory()->createComputopMapper()->getTestModeDescriptionValue($items);
    }

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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(TransferInterface $cardPaymentTransfer)
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

        $expectedMac = $this->getHashValue($this->getFactory()->createComputopMapper()->getMacResponseEncryptedValue($header));
        $this
            ->getFactory()
            ->createComputopConverter()
            ->checkMacResponse($header->getMac(), $expectedMac, $header->getMethod());

        return $header;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $responseArray
     * @param string $key
     *
     * @return null|string
     */
    public function getResponseValue(array $responseArray, $key)
    {
        return $this->getFactory()->createComputopConverter()->getResponseValue($responseArray, $key);
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
            $responseArray[ComputopApiConfig::DATA],
            $responseArray[ComputopApiConfig::LENGTH],
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
        $plainText = $this->getFactory()->createComputopMapper()->getDataPlainText($dataSubArray);
        $length = mb_strlen($plainText);

        $encryptedArray[ComputopApiConfig::DATA] = $this->getBlowfishEncryptedValue(
            $plainText,
            $length,
            $password
        );

        $encryptedArray[ComputopApiConfig::LENGTH] = $length;

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
     * @param string $plainText
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
