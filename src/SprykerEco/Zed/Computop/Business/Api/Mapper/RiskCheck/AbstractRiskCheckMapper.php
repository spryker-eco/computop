<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\RiskCheck;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractRiskCheckMapper implements ApiRiskCheckMapperInterface
{
    const TRANS_ID_SEPARATOR = '-';
    const MAC_SEPARATOR = '*';

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \Spryker\Service\UtilText\UtilTextService
     */
    protected $textService;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    abstract public function getDataSubArray(QuoteTransfer $quoteTransfer);

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $textService
     */
    public function __construct(
        ComputopServiceInterface $computopService,
        ComputopConfig $config,
        UtilTextServiceInterface $textService
    ) {
        $this->computopService = $computopService;
        $this->config = $config;
        $this->textService = $textService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildRequest(QuoteTransfer $quoteTransfer)
    {
        $encryptedArray = $this->getEncryptedArray($quoteTransfer);

        $data = $encryptedArray[ComputopApiConfig::DATA];
        $length = $encryptedArray[ComputopApiConfig::LENGTH];
        $merchantId = $this->config->getMerchantId();

        return $this->buildRequestData($data, $length, $merchantId);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getEncryptedArray(QuoteTransfer $quoteTransfer)
    {
        $encryptedArray = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($quoteTransfer),
            $this->config->getBlowfishPass()
        );

        return $encryptedArray;
    }

    /**
     * @param string $data
     * @param string $length
     * @param string $merchantId
     *
     * @return array
     */
    protected function buildRequestData($data, $length, $merchantId)
    {
        $requestData = [
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
            ComputopApiConfig::MERCHANT_ID => $merchantId,
        ];

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateTransId(QuoteTransfer $quoteTransfer)
    {
        $parameters = [
            $this->createUniqueSalt(),
            $quoteTransfer->getCustomer()->getCustomerReference(),
        ];

        return $this->textService->hashValue(implode(self::TRANS_ID_SEPARATOR, $parameters), Hash::SHA256);
    }

    /**
     * @return string
     */
    protected function createUniqueSalt()
    {
        return time() . self::TRANS_ID_SEPARATOR . rand(100, 1000);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $limit
     *
     * @return string
     */
    protected function getLimitedTransId(QuoteTransfer $quoteTransfer, $limit)
    {
        return substr($this->generateTransId($quoteTransfer), 0, $limit);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $transactionId
     *
     * @return string
     */
    protected function getMac(QuoteTransfer $quoteTransfer, $transactionId)
    {
        $macDataArray = [
            '',
            $transactionId,
            $this->config->getMerchantId(),
            $quoteTransfer->getTotals()->getPriceToPay(),
            $quoteTransfer->getCurrency()->getCode(),
        ];

        return $this->computopService->getHashValue(implode(self::MAC_SEPARATOR, $macDataArray));
    }
}
