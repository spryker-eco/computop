<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ComputopConsumerTransfer;
use Generated\Shared\Transfer\ComputopCredentialOnFileTransfer;
use Generated\Shared\Transfer\ComputopCredentialOnFileTypeTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopCustomerInfoTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class CreditCardMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopCreditCardPaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopPaymentTransfer */
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
        $computopPaymentTransfer->setUrl($this->config->getCreditCardInitActionUrl());

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopCreditCardPaymentTransfer
    {
        $computopPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD),
        );
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType($this->config->getCreditCardTxType());
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::CREDIT_CARD_SUCCESS, [], Router::ABSOLUTE_URL),
        );
        $computopPaymentTransfer->setUrlBack(
            $this->router->generate($this->config->getCallbackFailureRedirectPath(), [], Router::ABSOLUTE_URL),
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy()),
        );

        $computopPaymentTransfer = $this->expandCreditCardPaymentWithShipToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithBillToCustomer($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithShippingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithBillingAddress($computopPaymentTransfer, $quoteTransfer);
        $computopPaymentTransfer = $this->expandCreditCardPaymentWithCredentialOnFile($computopPaymentTransfer);

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer): array
    {
        $dataSubArray = [];
        $dataSubArray[ComputopApiConfig::MERCHANT_ID] = $computopCreditCardPaymentTransfer->getMerchantId();
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $computopCreditCardPaymentTransfer->getTransId();
        $dataSubArray[ComputopApiConfig::REF_NR] = $computopCreditCardPaymentTransfer->getRefNr();
        $dataSubArray[ComputopApiConfig::AMOUNT] = $computopCreditCardPaymentTransfer->getAmount();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $computopCreditCardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopApiConfig::URL_SUCCESS] = $computopCreditCardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopApiConfig::URL_NOTIFY] = $computopCreditCardPaymentTransfer->getUrlNotify();
        $dataSubArray[ComputopApiConfig::URL_FAILURE] = $computopCreditCardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopApiConfig::CAPTURE] = $computopCreditCardPaymentTransfer->getCapture();
        $dataSubArray[ComputopApiConfig::RESPONSE] = $computopCreditCardPaymentTransfer->getResponse();
        $dataSubArray[ComputopApiConfig::MAC] = $computopCreditCardPaymentTransfer->getMac();
        $dataSubArray[ComputopApiConfig::TX_TYPE] = $computopCreditCardPaymentTransfer->getTxType();
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $computopCreditCardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopApiConfig::ETI_ID] = $this->config->getEtiId();
        $dataSubArray[ComputopApiConfig::IP_ADDRESS] = $computopCreditCardPaymentTransfer->getClientIp();
        $dataSubArray[ComputopApiConfig::SHIPPING_ZIP] = $computopCreditCardPaymentTransfer->getShippingZip();

        $dataSubArray[ComputopApiConfig::MSG_VER] = ComputopApiConfig::PSD2_MSG_VERSION;
        $dataSubArray[ComputopApiConfig::BILL_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $computopCreditCardPaymentTransfer->getBillToCustomer()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::SHIP_TO_CUSTOMER] = $this->encodeRequestParameterData(
            $computopCreditCardPaymentTransfer->getShipToCustomer()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::BILLING_ADDRESS] = $this->encodeRequestParameterData(
            $computopCreditCardPaymentTransfer->getBillingAddress()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::SHIPPING_ADDRESS] = $this->encodeRequestParameterData(
            $computopCreditCardPaymentTransfer->getShippingAddress()->toArray(true, true),
        );
        $dataSubArray[ComputopApiConfig::CREDENTIAL_ON_FILE] = $this->encodeRequestParameterData(
            $computopCreditCardPaymentTransfer->getCredentialOnFile()->toArray(true, true),
        );

        return $dataSubArray;
    }

    /**
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return array
     */
    protected function getQueryParameters(string $merchantId, string $data, int $length): array
    {
        $queryData = parent::getQueryParameters($merchantId, $data, $length);

        if ($this->config->getCreditCardTemplateEnabled()) {
            $queryData[ComputopApiConfig::TEMPLATE] = $merchantId;
        }

        return $queryData;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithShipToCustomer(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        $addressTransfer = $this->getShippingAddressFromQuote($quoteTransfer);

        $computopConsumerTransfer = $this->mapAddressTransferToConsumerTransfer($addressTransfer, new ComputopConsumerTransfer());

        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer($computopConsumerTransfer)
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        return $computopCreditCardPaymentTransfer->setShipToCustomer($computopCustomerInfoTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithBillToCustomer(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopCreditCardPaymentTransfer->setBillToCustomer(
                $computopCreditCardPaymentTransfer->getShipToCustomer(),
            );

            return $computopCreditCardPaymentTransfer;
        }

        $computopConsumerTransfer = $this->mapAddressTransferToConsumerTransfer($quoteTransfer->getBillingAddress(), new ComputopConsumerTransfer());

        $computopCustomerInfoTransfer = (new ComputopCustomerInfoTransfer())
            ->setConsumer($computopConsumerTransfer)
            ->setEmail($quoteTransfer->getCustomer()->getEmail());

        return $computopCreditCardPaymentTransfer->setBillToCustomer($computopCustomerInfoTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithShippingAddress(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        $shippingAddress = $this->getShippingAddressFromQuote($quoteTransfer);

        $computopAddressTransfer = $this->getComputopAddressTransferByAddressTransfer($shippingAddress);

        return $computopCreditCardPaymentTransfer->setShippingAddress($computopAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithBillingAddress(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer,
        QuoteTransfer $quoteTransfer
    ): ComputopCreditCardPaymentTransfer {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            $computopCreditCardPaymentTransfer->setBillingAddress(
                $computopCreditCardPaymentTransfer->getShippingAddress(),
            );

            return $computopCreditCardPaymentTransfer;
        }

        $computopAddressTransfer = $this->getComputopAddressTransferByAddressTransfer($quoteTransfer->getBillingAddress());

        return $computopCreditCardPaymentTransfer->setBillingAddress($computopAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function expandCreditCardPaymentWithCredentialOnFile(
        ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
    ): ComputopCreditCardPaymentTransfer {
        $computopCredentialOnFileTransfer = (new ComputopCredentialOnFileTransfer())
            ->setType(
                (new ComputopCredentialOnFileTypeTransfer())
                    ->setUnscheduled(ComputopApiConfig::UNSCHEDULED_CUSTOMER_INITIATED_TRANSACTION),
            )
            ->setInitialPayment(true);

        $computopCreditCardPaymentTransfer->setCredentialOnFile($computopCredentialOnFileTransfer);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\ComputopConsumerTransfer $computopConsumerTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopConsumerTransfer
     */
    protected function mapAddressTransferToConsumerTransfer(
        AddressTransfer $addressTransfer,
        ComputopConsumerTransfer $computopConsumerTransfer
    ): ComputopConsumerTransfer {
        $computopConsumerTransfer->fromArray($addressTransfer->toArray(), true);
        if ($computopConsumerTransfer->getSalutation()) {
            $computopConsumerTransfer->setSalutation($this->getAcceptedSalutation($computopConsumerTransfer->getSalutation()));
        }

        return $computopConsumerTransfer;
    }

    /**
     * @param string $salutation
     *
     * @return string
     */
    protected function getAcceptedSalutation(string $salutation): string
    {
        $salutationMap = $this->config->getSalutationMap();
        $salutation = trim(str_replace('.', '', $salutation));

        return $salutationMap[$salutation] ?? 'Mr';
    }
}
