<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

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

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopConfig::PAYMENT_METHOD_PAYDIREKT)
        );
        $computopPaymentTransfer->setTransId(
            $this->getLimitedTransId($quoteTransfer, ComputopApiConfig::PAYDIRECT_TRANS_ID_LENGTH)
        );
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAYDIREKT_SUCCESS, [], Router::ABSOLUTE_URL)
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $computopPaymentTransfer->setShopApiKey($this->config->getPaydirektShopKey());
        $shippingAmount = $quoteTransfer->getTotals()->getExpenseTotal();
        if ($shippingAmount) {
            $computopPaymentTransfer->setShippingAmount((string)$shippingAmount);
        }
        $shoppingBasketAmount = $quoteTransfer->getTotals()->getSubtotal();
        if ($shoppingBasketAmount) {
            $computopPaymentTransfer->setShoppingBasketAmount((string)$shoppingBasketAmount);
        }

        $computopPaymentTransfer->setShippingFirstName($quoteTransfer->getShippingAddress()->getFirstName());
        $computopPaymentTransfer->setShippingLastName($quoteTransfer->getShippingAddress()->getLastName());
        $computopPaymentTransfer->setShippingZip($quoteTransfer->getShippingAddress()->getZipCode());
        $computopPaymentTransfer->setShippingCity($quoteTransfer->getShippingAddress()->getCity());
        $computopPaymentTransfer->setShippingCountryCode($quoteTransfer->getShippingAddress()->getIso2Code());

        return $computopPaymentTransfer;
    }
}
