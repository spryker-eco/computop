<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class CreditCardMapper extends AbstractMapper
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(TransferInterface $quoteTransfer)
    {
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
            $this->getUrlToComputop(
                $computopPaymentTransfer->getMerchantId(),
                $data,
                $length
            )
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopPaymentTransfer->setCapture(ComputopSharedConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType(ComputopConfig::TX_TYPE_ORDER);
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::CREDIT_CARD_SUCCESS))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getTestModeDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $cardPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer */
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

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return $this->config->getCreditCardInitAction();
    }

    /**
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    protected function getUrlToComputop($merchantId, $data, $length)
    {
        $queryData = [
            ComputopApiConfig::MERCHANT_ID => $merchantId,
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
            ComputopApiConfig::URL_BACK => $this->getAbsoluteUrl(
                $this->application->path($this->config->getCallbackFailureRedirectPath())
            ),
        ];

        if ($this->config->getCreditCardTemplateEnabled()) {
            $queryData[ComputopApiConfig::TEMPLATE] = $merchantId;
        }

        return $this->getActionUrl() . '?' . http_build_query($queryData);
    }
}
