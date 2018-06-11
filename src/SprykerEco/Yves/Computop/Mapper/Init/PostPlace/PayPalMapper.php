<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class PayPalMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setUrlNotify(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::NOTIFY_PATH_NAME))
        );
        $computopPaymentTransfer->setMac(
            $this->computopApiService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword()
        );

        $computopPaymentTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPaymentTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);
        $computopPaymentTransfer->setUrl(
            $this->getActionUrl(
                $this->config->getPayPalInitActionUrl(),
                $this->getQueryParameters(
                    $computopPaymentTransfer->getMerchantId(),
                    $decryptedValues[ComputopApiConfig::DATA],
                    $decryptedValues[ComputopApiConfig::LENGTH]
                )
            )
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPayPalPaymentTransfer();

        $computopPaymentTransfer->setCapture(ComputopSharedConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType(ComputopConfig::TX_TYPE_ORDER);
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::PAY_PAL_SUCCESS))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayPalPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = ComputopConfig::ETI_ID;
        $dataSubArray[ComputopApiConfig::NO_SHIPPING] = ComputopSharedConfig::PAY_PAL_NO_SHIPPING;

        return $dataSubArray;
    }
}
