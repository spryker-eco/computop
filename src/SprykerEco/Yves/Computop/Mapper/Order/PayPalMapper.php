<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

// TODO: update after check PayPal
class PayPalMapper extends AbstractMapper
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
        $computopPaymentTransfer->setTxType(ComputopConstants::TX_TYPE_ORDER);
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
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::URL_SUCCESS_F_N] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopConstants::URL_FAILURE_F_N] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopConstants::CAPTURE_F_N] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopConstants::RESPONSE_F_N] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopConstants::MAC_F_N] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopConstants::TX_TYPE_F_N] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopConstants::ORDER_DESC_F_N] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopConstants::ETI_ID_F_N] = ComputopConstants::ETI_ID;
        $dataSubArray[ComputopConstants::NO_SHIPPING_F_N] = self::NO_SHIPPING;

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::COMPUTOP_PAY_PAL_ORDER_ACTION);
    }

}
