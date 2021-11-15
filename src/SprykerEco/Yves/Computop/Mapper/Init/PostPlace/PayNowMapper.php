<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopBrowserInfoTransfer;
use Generated\Shared\Transfer\ComputopConsumerTransfer;
use Generated\Shared\Transfer\ComputopCredentialOnFileTransfer;
use Generated\Shared\Transfer\ComputopCredentialOnFileTypeTransfer;
use Generated\Shared\Transfer\ComputopCustomerInfoTransfer;
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
     * @var string
     */
    protected const HEADER_USER_AGENT = 'User-Agent';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'Accept';

    /**
     * @var int
     */
    protected const IFRAME_COLOR_DEPTH = 24;

    /**
     * @var int
     */
    protected const IFRAME_SCREEN_HEIGHT = 723;

    /**
     * @var int
     */
    protected const IFRAME_SCREEN_WIDTH = 1536;

    /**
     * @var string
     */
    protected const IFRAME_TIME_ZONE_OFFSET = '300';

    /**
     * @var bool
     */
    protected const IFRAME_IS_JAVA_ENABLED = false;

    /**
     * @var bool
     */
    protected const IFRAME_IS_JAVA_SCRIPT_ENABLED = true;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopPayNowPaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);
        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac(
                $this->createRequestTransfer($computopPaymentTransfer),
            ),
        );

        $decryptedValues = $this->computopApiService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword(),
        );

        $computopPaymentTransfer->setData($decryptedValues[ComputopApiConfig::DATA]);
        $computopPaymentTransfer->setLen($decryptedValues[ComputopApiConfig::LENGTH]);
        $computopPaymentTransfer->setUrl($this->config->getPayNowInitActionUrl());

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayNowPaymentTransfer
    {
        $computopPaymentTransfer = new ComputopPayNowPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW),
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getPayNowTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAY_NOW_SUCCESS, [], Router::ABSOLUTE_URL),
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy()),
        );

        $computopPaymentTransfer = $this->expandPayNowPaymentWithShipToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithBillToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithShippingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithBillingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithCredentialOnFile($computopPaymentTransfer);
        $computopPaymentTransfer = $this->expandPayNowPaymentWithBrowserInfo($computopPaymentTransfer);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer): array
    {
        $dataSubArray = [];
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayNowPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayNowPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayNowPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPayNowPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPayNowPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPayNowPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPayNowPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPayNowPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPayNowPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopPayNowPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPayNowPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopPayNowPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopPayNowPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopApiConfig::MSG_VER] = ComputopApiConfig::PSD2_MSG_VERSION;
        $dataSubArray[ComputopApiConfig::BILL_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getBillToCustomer()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::SHIP_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getShipToCustomer()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::BILLING_ADDRESS] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getBillingAddress()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::SHIPPING_ADDRESS] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getShippingAddress()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::CREDENTIAL_ON_FILE] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getCredentialOnFile()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::BROWSER_INFO] = $this->encodeRequestParameterData(
            $computopPayNowPaymentTransfer->getBrowserInfo()->toArray(true, true),
        );

        return $dataSubArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithShipToCustomer(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        $addressTransfer = $this->getShippingAddressFromQuote($quoteTransfer);

        $computopConsumerTransfer = (new ComputopConsumerTransfer())
            ->fromArray($addressTransfer->toArray(), true);

        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer($computopConsumerTransfer)
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        return $computopPayNowPaymentTransfer->setShipToCustomer($computopCustomerInfoTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithBillToCustomer(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopPayNowPaymentTransfer->setBillToCustomer(
                $computopPayNowPaymentTransfer->getShipToCustomer(),
            );

            return $computopPayNowPaymentTransfer;
        }

        $computopConsumerTransfer = (new ComputopConsumerTransfer())
            ->fromArray($quoteTransfer->getBillingAddress()->toArray(), true);

        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer($computopConsumerTransfer)
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        return $computopPayNowPaymentTransfer->setBillToCustomer($computopCustomerInfoTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithShippingAddress(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        $shippingAddress = $this->getShippingAddressFromQuote($quoteTransfer);

        $computopAddressTransfer = $this->getComputopAddressTransferByAddressTransfer($shippingAddress);

        return $computopPayNowPaymentTransfer->setShippingAddress($computopAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithBillingAddress(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopPayNowPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopPayNowPaymentTransfer->setBillingAddress(
                $computopPayNowPaymentTransfer->getShippingAddress(),
            );

            return $computopPayNowPaymentTransfer;
        }

        $computopAddressTransfer = $this->getComputopAddressTransferByAddressTransfer($quoteTransfer->getBillingAddress());

        return $computopPayNowPaymentTransfer->setBillingAddress($computopAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithCredentialOnFile(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
    ): ComputopPayNowPaymentTransfer {
        $computopCredentialOnFileTransfer = (new ComputopCredentialOnFileTransfer())
            ->setType(
                (new ComputopCredentialOnFileTypeTransfer())
                    ->setUnscheduled(ComputopApiConfig::UNSCHEDULED_CUSTOMER_INITIATED_TRANSACTION),
            )
            ->setInitialPayment(true);

        $computopPayNowPaymentTransfer->setCredentialOnFile($computopCredentialOnFileTransfer);

        return $computopPayNowPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowPaymentTransfer
     */
    protected function expandPayNowPaymentWithBrowserInfo(
        ComputopPayNowPaymentTransfer $computopPayNowPaymentTransfer
    ): ComputopPayNowPaymentTransfer {
        $computopBrowserInfoTransfer = (new ComputopBrowserInfoTransfer())
            ->setAcceptHeaders($this->request->headers->get(static::HEADER_ACCEPT))
            ->setIpAddress($this->request->getClientIp())
            ->setUserAgent($this->request->headers->get(static::HEADER_USER_AGENT))
            ->setJavaEnabled(static::IFRAME_IS_JAVA_ENABLED)
            ->setJavaScriptEnabled(static::IFRAME_IS_JAVA_SCRIPT_ENABLED)
            ->setColorDepth(static::IFRAME_COLOR_DEPTH)
            ->setScreenHeight(static::IFRAME_SCREEN_HEIGHT)
            ->setScreenWidth(static::IFRAME_SCREEN_WIDTH)
            ->setTimeZoneOffset(static::IFRAME_TIME_ZONE_OFFSET)
            ->setLanguage(mb_strtoupper($this->store->getCurrentLanguage()));

        $computopPayNowPaymentTransfer->setBrowserInfo($computopBrowserInfoTransfer);

        return $computopPayNowPaymentTransfer;
    }
}
