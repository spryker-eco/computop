<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayPalExpressMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getCustomer() === null) {
            $quoteTransfer->setCustomer(new CustomerTransfer());
        }
        /** @var \Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setPayPalMethod($this->config->getPayPalMethod());
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

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayPalExpressPaymentTransfer
    {
        $computopPaymentTransfer = new ComputopPayPalExpressPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAY_PAL_EXPRESS)
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getPayPalTxType());

        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::ROUTE_NAME_PAY_PAL_EXPRESS_PLACE_ORDER, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $this->mapAddressFromQuoteTransferToComputopPayPalExpressPaymentTransfer($quoteTransfer, $computopPaymentTransfer);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $computopPayPalPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function mapAddressFromQuoteTransferToComputopPayPalExpressPaymentTransfer(
        QuoteTransfer $quoteTransfer,
        ComputopPayPalExpressPaymentTransfer $computopPayPalPaymentTransfer
    ): ComputopPayPalExpressPaymentTransfer {
        $addressTransfer = $quoteTransfer->getBillingSameAsShipping() ?
            $quoteTransfer->getBillingAddress() : $quoteTransfer->getShippingAddress();

        if (!$addressTransfer) {
            return $computopPayPalPaymentTransfer;
        }

        $computopPayPalPaymentTransfer
            ->setFirstName($addressTransfer->getFirstName())
            ->setLastName($addressTransfer->getLastName())
            ->setAddressStreet($addressTransfer->getAddress1())
            ->setAddressStreet2($addressTransfer->getAddress2())
            ->setAddressCity($addressTransfer->getCity())
            ->setAddressState($addressTransfer->getState())
            ->setAddressZip($addressTransfer->getZipCode())
            ->setAddressCountryCode($addressTransfer->getIso2Code())
            ->setPhone($addressTransfer->getPhone());

        return $computopPayPalPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer $computopPayPalPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopPayPalExpressPaymentTransfer $computopPayPalPaymentTransfer): array
    {
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopPayPalPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopPayPalPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopPayPalPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopPayPalPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopPayPalPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::PAY_PAL_METHOD] = $computopPayPalPaymentTransfer->getPayPalMethod();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopPayPalPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopPayPalPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopPayPalPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopPayPalPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopPayPalPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopPayPalPaymentTransfer->getMac();

        return $dataSubArray;
    }
}
