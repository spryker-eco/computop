<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class PaydirektMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPaydirektPaymentTransfer();
        
        $computopPaymentTransfer->setCapture(ComputopConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setTransId(
            $this->getLimitedTransId($quoteTransfer, ComputopApiConfig::PAYDIRECT_TRANS_ID_LENGTH)
        );
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::PAYDIREKT_SUCCESS))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $computopPaymentTransfer->setShopApiKey($this->config->getPaydirektShopKey());
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
