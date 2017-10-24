<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order\PrePlace;

use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\Config\ComputopFieldNameConstants;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class PayPalMapper extends AbstractPrePlaceMapper
{
    const NO_SHIPPING = 1;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPayPalPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType(ComputopConfig::TX_TYPE_ORDER);
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::PAY_PAL_SUCCESS_PATH_NAME))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
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
        /** @var \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $cardPaymentTransfer */
        $dataSubArray[ComputopFieldNameConstants::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldNameConstants::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldNameConstants::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldNameConstants::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldNameConstants::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldNameConstants::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldNameConstants::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldNameConstants::TX_TYPE] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopFieldNameConstants::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopFieldNameConstants::ETI_ID] = ComputopConfig::ETI_ID;
        $dataSubArray[ComputopFieldNameConstants::NO_SHIPPING] = self::NO_SHIPPING;

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::PAY_PAL_ORDER_ACTION);
    }
}
