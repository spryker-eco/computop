<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

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
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer)
            )
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

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAY_PAL)
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getPayPalTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAY_PAL_SUCCESS, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer)
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayPalPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayPalPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayPalPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPayPalPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPayPalPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPayPalPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPayPalPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPayPalPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPayPalPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopPayPalPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPayPalPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::NO_SHIPPING] = ComputopSharedConfig::PAY_PAL_NO_SHIPPING;
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopPayPalPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPayPalPaymentTransfer->getShippingZip();

        return $dataSubArray;
    }
}
