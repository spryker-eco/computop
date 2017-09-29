<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class SofortMapper extends AbstractMapper
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopSofortPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopSofortPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::SOFORT_SUCCESS_PATH_NAME))
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
        /** @var \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer $cardPaymentTransfer */
        $dataSubArray[ComputopFieldNameConstants::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldNameConstants::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldNameConstants::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldNameConstants::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldNameConstants::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldNameConstants::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldNameConstants::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::COMPUTOP_SOFORT_ORDER_ACTION);
    }

}
