<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order\PostPlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class PaydirektMapper extends AbstractPostPlaceMapper
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPaydirektPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::PAYDIREKT_SUCCESS_PATH_NAME))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getTestModeDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $computopPaymentTransfer->setShopApiKey(Config::get(ComputopConstants::PAYDIREKT_SHOP_KEY));
        $computopPaymentTransfer->setShippingAmount($quoteTransfer->getTotals()->getExpenseTotal());
        $computopPaymentTransfer->setShoppingBasketAmount($quoteTransfer->getTotals()->getSubtotal());
        $computopPaymentTransfer->setShippingFirstName($quoteTransfer->getShippingAddress()->getFirstName());
        $computopPaymentTransfer->setShippingLastName($quoteTransfer->getShippingAddress()->getLastName());
        $computopPaymentTransfer->setShippingZip($quoteTransfer->getShippingAddress()->getZipCode());
        $computopPaymentTransfer->setShippingCity($quoteTransfer->getShippingAddress()->getCity());
        $computopPaymentTransfer->setShippingCountryCode($quoteTransfer->getShippingAddress()->getIso2Code());

        return $computopPaymentTransfer;
    }
}
