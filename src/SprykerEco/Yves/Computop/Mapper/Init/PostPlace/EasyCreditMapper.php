<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class EasyCreditMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setUrlNotify(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::NOTIFY_PATH_NAME))
        );
        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword()
        );

        $length = $decryptedValues[ComputopApiConfig::LENGTH];
        $data = $decryptedValues[ComputopApiConfig::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl(
            $this->getActionUrl(
                $this->config->getEasyCreditInitActionUrl(),
                $this->getQueryParameters($computopPaymentTransfer->getMerchantId(), $data, $length)
            )
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopEasyCreditPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::EASY_CREDIT_SUCCESS))
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopEasyCreditPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $cardPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::EVENT_TOKEN] = ComputopApiConfig::EVENT_TOKEN_INIT;

        return $dataSubArray;
    }
}