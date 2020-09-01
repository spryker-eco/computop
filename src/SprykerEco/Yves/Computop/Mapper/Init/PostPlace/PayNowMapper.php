<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayNowMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setUrlNotify(
            $this->router->generate(ComputopRouteProviderPlugin::NOTIFY_PATH_NAME, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer)
            )
        );

        $computopPaymentTransfer->setUrl(
            $this->config->getPayNowInitActionUrl()
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPayNowPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW)
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getPayNowTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAY_NOW_SUCCESS, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayNowPaymentTransfer $cardPaymentTransfer)
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
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $cardPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();

        $dataSubArray[ComputopApiConfig::BILL_TO_CUSTOMER] = base64_encode(json_encode([]));
        $dataSubArray[ComputopApiConfig::SHIP_TO_CUSTOMER] = base64_encode(json_encode([]));
        $dataSubArray[ComputopApiConfig::BILLING_ADDRESS] = base64_encode(json_encode([]));
        $dataSubArray[ComputopApiConfig::SHIPPING_ADDRESS] = base64_encode(json_encode([]));
        $dataSubArray[ComputopApiConfig::CREDENTIAL_ON_FILE] = base64_encode(json_encode([]));

        return $dataSubArray;
    }
}
