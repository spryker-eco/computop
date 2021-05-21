<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Complete;

use Generated\Shared\Transfer\ComputopPayPalExpressCompleteResponseTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;

class PayPalExpressCompeteMapper implements PayPalExpressCompeteMapperInterface
{
    /**
     * @var \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected $computopApiService;

    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    protected $config;

    public function __construct(ComputopApiServiceInterface $computopApiService, ComputopConfigInterface $config)
    {
        $this->computopApiService = $computopApiService;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function createComputopPayPalExpressCompleteTransfer(QuoteTransfer $quoteTransfer): ComputopPayPalExpressCompleteTransfer
    {
        $computopPayPalExpressCompleteTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPayPalExpressCompleteTransfer),
            $this->config->getBlowfishPassword()
        );

        $computopPayPalExpressCompleteTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPayPalExpressCompleteTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);

        return $computopPayPalExpressCompleteTransfer;
    }

    /**
     * @param array $computopPayPalExpressCompleteResponse
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer $computopPayPalExpressCompleteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer
     */
    public function mapComputopPayPalExpressCompleteResponse(
        array $computopPayPalExpressCompleteResponse,
        ComputopPayPalExpressCompleteTransfer $computopPayPalExpressCompleteTransfer
    ): ComputopPayPalExpressCompleteTransfer {
        $computopPayPalExpressCompleteResponseTransfer = new ComputopPayPalExpressCompleteResponseTransfer();

        $decryptedArray = $this
            ->computopApiService
            ->decryptResponseHeader($computopPayPalExpressCompleteResponse, $this->config->getBlowfishPassword());

        $computopApiResponseHeaderTransfer = $this->computopApiService->extractResponseHeader(
            $decryptedArray,
            'COMPLETE'
        );

        $computopPayPalExpressCompleteResponseTransfer->setHeader($computopApiResponseHeaderTransfer);
        $computopPayPalExpressCompleteResponseTransfer->setMerchantId($computopApiResponseHeaderTransfer->getMId());
        $computopPayPalExpressCompleteResponseTransfer->setPayId($computopApiResponseHeaderTransfer->getPayId());
        $computopPayPalExpressCompleteResponseTransfer->setTransId($computopApiResponseHeaderTransfer->getTransId());
        $computopPayPalExpressCompleteResponseTransfer->setStatus($computopApiResponseHeaderTransfer->getStatus());
        $computopPayPalExpressCompleteResponseTransfer->setIsSuccess($computopApiResponseHeaderTransfer->getIsSuccess());

        $computopPayPalExpressCompleteTransfer->setPayPalExpressCompleteResponse($computopPayPalExpressCompleteResponseTransfer);

        return $computopPayPalExpressCompleteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayPalExpressCompleteTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment()->getComputopPayPalExpress();

        $computopPayPalExpressCompleteTransfer = new ComputopPayPalExpressCompleteTransfer();
        $computopPayPalExpressCompleteTransfer->setMerchantId($this->config->getMerchantId());
        $computopPayPalExpressCompleteTransfer->setPayId($paymentTransfer->getPayId());
        $computopPayPalExpressCompleteTransfer->setTransactionId($paymentTransfer->getTransId());
        $computopPayPalExpressCompleteTransfer->setRefNr($paymentTransfer->getRefNr());
        $computopPayPalExpressCompleteTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPayPalExpressCompleteTransfer->setCurrency($paymentTransfer->getCurrency());

        return $computopPayPalExpressCompleteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer $computopPayPalExpressCompleteTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayPalExpressCompleteTransfer $computopPayPalExpressCompleteTransfer): array
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayPalExpressCompleteTransfer->getTransactionId();
        $dataSubArray[ComputopApiConfig::MERCHANT_ID] = $computopPayPalExpressCompleteTransfer->getMerchantId();
        $dataSubArray[ComputopApiConfig::PAY_ID] = $computopPayPalExpressCompleteTransfer->getPayId();
        $dataSubArray[ComputopApiConfig::REF_NR] = $computopPayPalExpressCompleteTransfer->getRefNr();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayPalExpressCompleteTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayPalExpressCompleteTransfer->getCurrency();

        return $dataSubArray;
    }
}
